<?php

namespace Tests\Feature;

use Tests\Traits\WithDatabaseMany;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Traits\WithErrorJson;

class StoreParticipantTest extends TestCase
{
    use WithDatabaseMany;
    use WithErrorJson;

    public function test_participant_creation(): void
    {
        $user = User::factory()->create();
        $body = $this->body($this->faker->randomDigit() + 1);

        $this->actingAs($user)
            ->postJson($this->uri(), $body)
            ->assertCreated()
            ->assertJsonStructure($this->expectedJsonStructure());

        $users = collect($body)->map(fn ($user) => [
            'name' => $user['name'],
            'email' => $user['email'],
        ]);

        $this->assertDatabaseHasMany(User::class, $users->toArray());
        $this->assertDatabaseHasMany(Participant::class, Arr::only($body, ['phone', 'age']));
    }

    public function test_cannot_create_participants_with_same_email(): void
    {
        $user = User::factory()->create();
        $email = $this->faker->email();
        $body = collect($this->body(participant_count: 2))
            ->map(fn ($field) => [...$field, 'email' => $email])
            ->toArray();

        $this->actingAs($user)
            ->postJson($this->uri(), $body)
            ->assertUnprocessable()
            ->assertJsonStructure($this->errorJsonStructure())
            ->assertJson($this->errorJson('The email has already been taken.'));

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseEmpty(Participant::class);
    }

    private function expectedJsonStructure(): array
    {
        return [
            'data' => [
                '*' => [
                    'participant' => [
                        'uuid',
                        'phone',
                        'age',
                    ],
                    'user' => [
                        'uuid',
                        'name',
                        'email',
                    ]
                ]
            ]
        ];
    }

    private function expectedJson(array $body): array
    {
        $participant = Participant::firstOrFail();
        $user = User::firstOrFail();

        return [
            'data' => [
                'participant' => [
                    'uuid' => $participant->uuid,
                    'phone' => $body['phone'],
                    'age' => $body['age'],
                ],
                'user' => [
                    'uuid' => $user->uuid,
                    'name' => $body['name'],
                    'email' => $body['email'],
                ]
            ]
        ];
    }

    private function body(int $participant_count = 1): array
    {
        return collect()
            ->times($participant_count, fn () => Participant::factory()->definition())
            ->toArray();
    }

    private function uri(): string
    {
        return route('participants.store');
    }
}
