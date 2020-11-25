<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscriptionReceipt extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_subscription_receipts';

    /**
     * @var array
     */
    protected $guarded = ['id'];
}
