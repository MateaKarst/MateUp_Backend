<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSessions extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'user_id',
        'location',
        'max_participants',
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
