<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class StoreParticipantRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'max:255'],
            '*.email' => [
                'required', 
                'email', 
                'max:255', 
                fn ($_, $value, $fail) => $this->checkEmail($value, $fail)
            ],
            '*.password' => ['required', 'string', 'min:8', 'max:255'],
            '*.age' => ['nullable', 'integer', 'required', 'min:1'],
            '*.phone' => ['nullable', 'string', 'required', 'max:20']
        ];
    }

    private function checkEmail(string $email, callable $fail): void
    {
        if ($this->emailIsTaken($email)) {
            $fail(Lang::get('validation.unique', ['attribute' => 'email']));
        }
    }

    private function emailIsTaken(string $email): bool
    {
        return collect($this->all())->countBy(fn ($field) => $field['email'] === $email)->get(true, 0) > 1;
    }

    protected function passedValidation(): void
    {
        throw_if(empty($this->all()), ValidationException::withMessages([
            'message' => 'Cannot create empty participants.'
        ]));
    }
}
