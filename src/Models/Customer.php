<?php

namespace Viviniko\Customer\Models;

use Viviniko\Customer\Notifications\PasswordUpdated;
use Viviniko\Customer\Notifications\Registered;
use Viviniko\Customer\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;
use Viviniko\Favorite\Favoritor;

class Customer extends Authenticatable
{
    use Notifiable, Favoritor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'phone', 'is_active', 'reg_ip', 'log_num', 'log_date', 'log_ip'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('customer.customers_table');
    }

    /**
     * Always encrypt password when it is updated.
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getNameAttribute()
    {
        $name = "{$this->first_name} {$this->last_name}";

        return trim($name) ? $name : ucfirst(explode('@', $this->email, 2)[0]);
    }

    public function socialNetworks()
    {
        return $this->hasOne(CustomerSocialNetworks::class, 'customer_id');
    }

    public function addresses()
    {
        return $this->morphMany(Config::get('address.address'), 'addressable');
    }

    public function orders()
    {
        return $this->hasMany(config('sale.order'));
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    /**
     * Send the registered notification.
     *
     * return void
     */
    public function sendRegisteredNotification()
    {
        $this->notify(new Registered());
    }

    /**
     * Send the password updated notification.
     *
     * return void
     */
    public function sendPasswordUpdatedNotification()
    {
        $this->notify(new PasswordUpdated());
    }

}