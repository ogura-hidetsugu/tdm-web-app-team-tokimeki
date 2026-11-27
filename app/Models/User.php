<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'display_name',
        'email',
        'password',
        'is_active',
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'memberships');
    }

    /**
     * ユーザーが所属する代表グループを取得する。
     */
    public function primaryGroup(): ?Group
    {
        return $this->groups()
            ->wherePivot('is_active', true)
            ->orderBy('groups.id')
            ->first()
            ?? $this->groups()->orderBy('groups.id')->first();
    }

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
            'password' => 'hashed',
        ];
    }
}
