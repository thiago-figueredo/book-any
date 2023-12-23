<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Lang;

class StoreEventRequest extends FormRequest
{
    use WithFaker;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required', 'max:255'],
            'start' => ['date', 'required', 'after:now'],
            'end' => ['date', 'nullable', 'after:start'],
            'participants_limit' => ['integer', 'nullable', 'min:0'],
            'participants_count' => [
                'integer', 
                'nullable', 
                'min:0', 
                fn ($_, $value, $fail) => $this->validateParticipantsCount($value, $fail),
            ],
        ];
    }

    public function validateParticipantsCount(int $value, callable $fail): void 
    {
        $replace_fields = [
            'attribute' => 'participants count', 
            'value' => 'participants limit', 
            'max' => $this->participants_limit
        ];

        if ($this->participants_limit !== null && $value > $this->participants_limit) {
            $fail(Lang::get('validation.lte.numeric', $replace_fields));
        }
    }
}
