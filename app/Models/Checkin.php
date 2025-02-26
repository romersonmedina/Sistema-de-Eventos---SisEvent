<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    // Defina a relação com Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
