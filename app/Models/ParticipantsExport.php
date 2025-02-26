<?php
namespace App\Exports;

use App\Models\Checkin;
use Maatwebsite\Excel\Concerns\FromCollection;

/*
class ParticipantsExport implements FromCollection
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function collection()
    {
        return Checkin::where('event_id', $this->eventId)
                      ->with('user')
                      ->get(['user_id', 'status', 'created_at']);
    }
}
*/
