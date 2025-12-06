<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'mobile_number',
        'email',
        'password',
        'date_of_birth',
        'gender',
        'alternative_mobile',
        'street',
        'city',
        'country',
        'initiation_date',
        'spiritual_master',
        'bhakti_sadan_id',
        'enabled',
        'photo',
        'marital_status',
        'marriage_anniversary_date',
        'state',
        'pincode',
        'education_id',
        'profession_id',
        'initiated',
        'rounds',
        'second_initiation',
        'life_membership',
        'life_member_no',
        'temple',
        'blood_group_id',
    ];

    public function bloodGroup()
    {
        return $this->belongsTo(BloodGroup::class);
    }

    public function bhaktiSadan()
    {
        return $this->belongsTo(BhaktiSadan::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'user_languages');
    }

    public function sevas()
    {
        return $this->belongsToMany(Seva::class, 'user_sevas');
    }

    public function dependants()
    {
        return $this->hasMany(Dependant::class);
    }

    public function shikshaLevels()
    {
        return $this->belongsToMany(ShikshaLevel::class, 'user_shiksha_level');
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
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
            'enabled' => 'boolean',
        ];
    }
}
