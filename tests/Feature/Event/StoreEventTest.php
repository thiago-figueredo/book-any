<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class StoreEventTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_event_creation(): void
    {
        $body = $this->body();

        $this->actingAs($this->user)
            ->postJson($this->uri(), $body)
            ->assertCreated()
            ->assertJsonStructure($this->successJsonStructure())
            ->assertJson($this->successJson($body));

        $this->assertDatabaseCount(Event::class, 1);
        $this->assertDatabaseHas(Event::class, [...$body, 'is_finished' => false]);
    }

    public function test_participants_count_must_be_less_than_participants_limit(): void
    {
        $participants_limit = $this->faker->randomNumber();
        $body = [
            ...$this->body(),
            'participants_count' => $participants_limit + 1,
            'participants_limit' => $participants_limit,
        ];

        $error_message = 'The participants count field must be less than or equal to participants limit.';

        $this->actingAs($this->user)
            ->postJson($this->uri(), $body)
            ->assertUnprocessable()
            ->assertJsonStructure($this->errorJsonStructure())
            ->assertJson($this->errorJson($error_message));

        $this->assertDatabaseEmpty(Event::class);
    }

    public function test_participants_count_does_not_have_upper_bound_when_participants_limit_is_null(): void
    {
        $participants_count = $this->faker->randomNumber();
        $body = [
            ...$this->body(),
            'participants_count' => $participants_count,
            'participants_limit' => null,
        ];

        $this->actingAs($this->user)
            ->postJson($this->uri(), $body)
            ->assertCreated()
            ->assertJsonStructure($this->successJsonStructure())
            ->assertJson($this->successJson($body));

        $this->assertDatabaseCount(Event::class, 1);
        $this->assertDatabaseHas(Event::class, [...$body, 'is_finished' => false]);
    }

    private function errorJson(string $message): array
    {
        return compact('message');
    }

    private function successJson(array $body): array
    {
        return [
            'data' => [
                ...$body,
                'is_finished' => false,
                'uuid' => Event::first()->uuid,
            ]
        ];
    }

    private function body(): array
    {
        return Event::factory()->definition();
    }

    private function errorJsonStructure(): array
    {
        return [
            'message',
            'errors'
        ];
    }

    private function successJsonStructure(): array
    {
        return [
            'data' => [
                'uuid',
                'name',
                'participants_count',
                'participants_limit',
                'is_finished',
                'start',
                'end',
            ],
        ];
    }

    private function uri(): string
    {
        return route('events.store');
    }
}
