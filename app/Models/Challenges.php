<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenges extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'club_address',
        'challenge_image_url',
        'goal',
        'workout_category',
        // 'frequency_based',
        'time_based',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        // The session belongs to a user
        return $this->belongsTo(User::class);
    }
}
