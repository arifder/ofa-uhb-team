<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
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
        'name',
        'email',
        'password',
        'username',
        'role',
        'fakultas_id',
        'status',
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

    public function isAdminKas(): bool {
        return in_array($this->role, [
            'admin_kas_fst',
            'admin_kas_fis',
            'super_admin'
        ]);
    }

    public function isAdminNotulensi(): bool {
        return in_array($this->role, [
            'admin_notulensi_fst',
            'admin_notulensi_fis',
            'super_admin'
        ]);
    }

    public function isSuperAdmin(): bool {
        return $this->role === 'super_admin';
    }

    public function getRoleLabelAttribute(): string {
        return match($this->role) {
            'super_admin'          => 'Super Admin',
            'admin_kas_fst'        => 'Admin Kas FST',
            'admin_notulensi_fst'  => 'Admin Notulensi FST',
            'admin_kas_fis'        => 'Admin Kas FIS',
            'admin_notulensi_fis'  => 'Admin Notulensi FIS',
            'kepala_unit'          => 'Kepala Unit',
            'dosen'                => 'Dosen',
            default                => $this->role
        };
    }

    public function getRoleBadgeColorAttribute(): array {
        return match($this->role) {
            'super_admin'         => ['bg'=>'#dbeafe','text'=>'#1d4ed8'],
            'admin_kas_fst'       => ['bg'=>'#dbeafe','text'=>'#1e40af'],
            'admin_notulensi_fst' => ['bg'=>'#e0f2fe','text'=>'#0284c7'],
            'admin_kas_fis'       => ['bg'=>'#ffedd5','text'=>'#c2410c'],
            'admin_notulensi_fis' => ['bg'=>'#fef3c7','text'=>'#92400e'],
            'kepala_unit'         => ['bg'=>'#d1fae5','text'=>'#065f46'],
            'dosen'               => ['bg'=>'#ede9fe','text'=>'#5b21b6'],
            default               => ['bg'=>'#f3f4f6','text'=>'#374151']
        };
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }
        return in_array($this->role, $roles);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'user_id');
    }
}
