<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    // Attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'created_at',
        'updated_at',
    ];

    // User relationship
    public function user()
    {
        // The admin belongs to a user
        return $this->belongsTo(User::class);
    }
}
