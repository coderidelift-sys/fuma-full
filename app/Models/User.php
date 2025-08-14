<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'whatsapp',
        'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute($value): string
    {
        return $this->avatar ? asset($this->avatar) : asset('/materialize/assets/img/avatars/1.png');
    }

    // FUMA System Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function organizedTournaments()
    {
        return $this->hasMany(Tournament::class, 'organizer_id');
    }

    public function managedTeams()
    {
        return $this->hasMany(Team::class, 'manager_id');
    }

    public function committees()
    {
        return $this->hasMany(Committee::class);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles()->where('name', $role)->exists();
        }
        return $this->roles()->where('id', $role->id)->exists();
    }

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            return $this->roles()->whereIn('name', $roles)->exists();
        }
        return $this->hasRole($roles);
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isOrganizer()
    {
        return $this->hasRole('organizer');
    }

    public function isCommittee()
    {
        return $this->hasRole('committee');
    }

    public function isManager()
    {
        return $this->hasRole('manager');
    }
}
