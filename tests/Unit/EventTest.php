<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_creation()
    {
        $event = Event::factory()->create([
            'name' => 'Evento de Teste',
            'date' => '2025-05-10',
            'location' => 'Auditório Principal'
        ]);

        $this->assertDatabaseHas('events', [
            'name' => 'Evento de Teste',
            'date' => '2025-05-10',
            'location' => 'Auditório Principal'
        ]);
    }
}

