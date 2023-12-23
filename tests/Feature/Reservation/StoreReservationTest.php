<?php

namespace Tests\Feature;

use App\Constants\ReservationStatus;
use App\Exceptions\EventFinishedException;
use App\Exceptions\EventFullException;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\WithErrorJson;

class StoreReservationTest extends TestCase
{
    use WithErrorJson;

    private User $user;
    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->event = Event::factory()->create();
    }

    public function test_one_reservation_creation(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson($this->uri($this->event))
            ->assertCreated()
            ->assertJsonStructure($this->successJsonStructure());

        $expected_reservation = $this->expectedJson();

        $response->assertJson($expected_reservation);

        $this->assertDatabaseCount(Reservation::class, 1);
        $this->assertDatabaseHas(Reservation::class, $expected_reservation['data']);
    }

    public function test_cannot_reserve_event_with_max_participants(): void
    {
        $participants_limit = $this->event->participants_limit ?? 1;

        $this->event->update([
            'participants_limit' => $participants_limit,
            'participants_count' => $participants_limit + 1,
        ]);

        $this->actingAs($this->user)
            ->postJson($this->uri($this->event))
            ->assertBadRequest()
            ->assertJsonStructure($this->errorJsonStructure())
            ->assertJson(EventFullException::response());

        $this->assertDatabaseEmpty(Reservation::class);
    }

    public function test_cannot_reserve_finished_event(): void
    {
        $this->event->finish();

        $this->actingAs($this->user)
            ->postJson($this->uri($this->event))
            ->assertBadRequest()
            ->assertJsonStructure($this->errorJsonStructure())
            ->assertJson(EventFinishedException::response());

        $this->assertDatabaseEmpty(Reservation::class);
    }

    private function expectedJson(): array
    {
        return [
            'data' => [
                'uuid' => Reservation::first()->uuid,
                'event_uuid' => $this->event->uuid,
                'status' => ReservationStatus::OPEN,
            ]
        ];
    }

    private function successJsonStructure(): array
    {
        return [
            'data' => [
                'uuid',
                'event_uuid',
                'status',
                'created_at',
                'updated_at',
            ],
        ];
    }

    private function uri(Event $event): string
    {
        return route('reservations.store', compact('event'));
    }
}
