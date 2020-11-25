<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'email_verified_at', 'password'
    ];

    /**
     * Login rules set;
     *
     * @return void
     */
    public static function subscriptionInitiationRules()
    {
        return [
            'user_id' => ['required'],
            'initiated_at' => ['required'],
            'subscription_valid_until' => ['required'],
        ];
    }
}
