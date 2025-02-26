<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $casts = [
        'items' => 'array'
    ];

    protected $dates = ['date'];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function users()
    {
    return $this->belongsToMany(User::class, 'event_user'); // Certifique-se de que está buscando os inscritos
    }

    public function checkins()
    {
        return $this->users()->wherePivot('attended', true); // Relaciona com os usuários que marcaram presença
    }

    public function participants()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('cancelled')
            ->withTimestamps();
    }

}
