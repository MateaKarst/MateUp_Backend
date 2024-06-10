<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSessions extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trainer_id',
        'session_image_url',
        'goal',
        'max_participants',
        'available_spots',
        'club_address',
        'session_date',
        'start_time',
        'end_time',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        // The session belongs to a user
        return $this->belongsTo(User::class);
    }

}
