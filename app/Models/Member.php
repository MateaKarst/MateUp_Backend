<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    // Attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'home_club_address',
        'level_fitness',
        'workout_types',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'workout_types' => 'array',
        ];
    }

    // User relationship
    public function user()
    {
        // The member belongs to a user
        return $this->belongsTo(User::class);
    }
}
