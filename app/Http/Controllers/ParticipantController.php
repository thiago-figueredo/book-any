<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ParticipantController extends Controller
{
    public function store(StoreParticipantRequest $request): JsonResponse
    {
        $response = collect($request->validated())->map(
            function ($fields) {
                $user = $this->createUser($fields);
                $participant = $this->createParticipant($user, $fields);

                return compact(['participant', 'user']);
            }
        );

        return response()->json(['data' => $response], JsonResponse::HTTP_CREATED);
    }

    private function createUser(array $fields): User
    {
        return User::create([
            'name' => $fields['name'], 
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);
    }

    private function createParticipant(User $user, array $fields): Participant
    {
        return $user->participant()->create([
            'phone' => $fields['phone'], 
            'age' => $fields['age']
        ]);
    }
}
