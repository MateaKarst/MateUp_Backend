<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buddies extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buddy_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buddy()
    {
        return $this->belongsTo(User::class, 'buddy_id');
    }
}
