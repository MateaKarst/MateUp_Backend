<?php

namespace App\Models;

use App\Models\Member;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    // Attributes that are mass assignable.
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'name',
        'surname',
        'phone',
        'bio',
        'profile_image_url',
        'facebook',
        'instagram',
        'twitter',
        'created_at',
        'updated_at',
        'user_token'
    ];

    // Attributes that should be hidden for serialization.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Attributes that should be cast.
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // User relationship member
    public function member()
    {
        // The user has one member
        return $this->hasOne(Member::class);
    }

    // User relationship trainer
    public function trainer()
    {
        // The user has one trainer
        return $this->hasOne(Trainer::class);
    }

    // User relationship admin
    public function admin()
    {
        // The user has one trainer
        return $this->hasOne(Admin::class);
    }

    // Get JWT identifier
    public function getJWTIdentifier()
    {
        // Get JWT identifier
        return $this->getKey();
    }

    // Get JWT custom claims
    public function getJWTCustomClaims()
    {
        // Returns empty array
        return [];
    }

    public function buddies()
    {
        return $this->hasMany(Buddies::class, 'user_id');
    }

    public function buddyBuddies()
    {
        return $this->hasMany(Buddies::class, 'buddy_id');
    }

    // Create user profiles automatically --> member and admin
    protected static function boot()
    {
        // Boot user model
        parent::boot();

        // Create profile automatically when the role is created
        static::created(function ($user) {

            // Check if user has role member
            if ($user->role === 'member') {

                $addresses = [
                    'Bruul 107, 2800 Mechelen',
                    'Zwartebeekstraat 14, 2800 Mechelen',
                    'Sint-Jacobsstraat 1, 2800 Mechelen',
                ];

                $address = $addresses[array_rand($addresses)];

                // Create member
                Member::create([
                    'user_id' => $user->id,
                    'home_club_address' =>   $address,
                    'level_fitness' => 'beginner'
                ]);
            }

            // Check if user has role admin
            if ($user->role === 'admin') {
                // Create admin
                Admin::create([
                    'user_id' => $user->id
                ]);
            }
        });

        static::deleting(function ($user) {
            // Delete all buddy relationships where the user is either user_id or buddy_id
            $user->buddies()->delete();
            $user->buddyBuddies()->delete();
        });
    }
}
