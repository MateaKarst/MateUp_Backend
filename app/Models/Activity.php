<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Activity extends Model
{
    use HasFactory;

    // Attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'member_id',
        'workout_type',
        'location',
        'date',
        'start_time',
        'end_time',
        'description',
        'repeats',
        'recurrence',
        'reminder_date',
        'reminder_time',
    ];

    // Attributes that should be cast.
    protected $casts = [
        'date' => 'date',
        'start_time' => 'time',
        'end_time' => 'time',
        'reminder_date' => 'date',
        'reminder_time' => 'time',
    ];

    public function user()
    {
        // The member belongs to a user
        return $this->belongsTo(User::class);
    }
}
