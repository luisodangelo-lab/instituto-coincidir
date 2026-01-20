<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// IMPORTS de relaciones
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\WalletMovement;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dni',
        'phone_whatsapp',
        'phone_whatsapp_verified_at',
        'account_state',
        'role',
        'wallet_balance',   // ✅ si ya agregaste la columna
        'avatar_path',      // ✅ si ya agregaste avatar
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'phone_whatsapp_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ✅ Relaciones (ADENTRO de la clase)
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function walletMovements()
    {
        return $this->hasMany(WalletMovement::class);
    }
}
