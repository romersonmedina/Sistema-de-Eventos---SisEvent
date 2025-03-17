<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class AttendanceTest extends TestCase
{
    use DatabaseMigrations;


    public function test_user_attendance_can_be_marked()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $event->users()->attach($user->id, ['attended' => true]);

        $this->assertDatabaseHas('event_user', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'attended' => true
        ]);
    }
}

