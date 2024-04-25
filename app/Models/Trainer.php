<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    // Attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'home_club_address',
        'expertise',
        'education',
        'languages',
        'stripe_id',
        'stripe_url',
        'rate_currency',
        'rate_amount',
        'content_about',
        'created_at',
        'updated_at',
    ];

    // User relationship
    public function user()
    {
        // The trainer belongs to a user
        return $this->belongsTo(User::class);
    }
}
