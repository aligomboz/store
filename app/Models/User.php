<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Mindscms\Entrust\Traits\EntrustUserWithPermissionsTrait;
use Nicolaslopezj\Searchable\SearchableTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,EntrustUserWithPermissionsTrait,SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'first_name',
        'laset_name',
        'mobile',
        'image',
        'status',
        'username',
        'password',
    ];

    protected $appends = ['full_name'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $searchable = [
        'columns' => [
            'users.first_name' => 10,
            'users.laset_name' => 10,
            'users.username' => 10,
            'users.email' => 10,
            'users.mobile' => 10,
        ]
    ];
    public function getFullNameAttribute(): string
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->laset_name);
    }
    
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class,'user_id','id');
    }
    public function status(): string
    {
        return $this->status ? 'Active' : 'Inactive';
    }
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    // public function orders(): HasMany
    // {
    //     return $this->hasMany(Order::class);
    // }

}
