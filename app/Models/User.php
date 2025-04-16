<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'profile_url',
        'password',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // one to many from user to rating
    public function rating()
    {
        return $this->hasMany(Rating::class, 'id_user', 'id');
    }

    // one to one from user to merchant
    public function merchant()
    {
        return $this->hasOne(Merchant::class, 'id_user', 'id');
    }

    // one to many from user to booking
    public function booking()
    {
        return $this->hasMany(Booking::class, 'id_user', 'id');
    }

    // Relasi untuk toko favorit
    public function toko_favorit()
    {
        return $this->hasMany(TokoFavorit::class, 'id_user');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'id_user');
    }
}
