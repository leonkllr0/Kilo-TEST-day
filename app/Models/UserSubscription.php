<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_subscriptions';

     /**
     * @var array
     */
    protected $guarded = ['id'];
}
