<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_for_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $event->users()->attach($user->id);

        $this->assertDatabaseHas('event_user', [
            'user_id' => $user->id,
            'event_id' => $event->id
        ]);
    }
}

