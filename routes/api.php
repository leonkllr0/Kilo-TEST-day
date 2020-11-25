<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['namespace' => 'API'], function () {
    Route::match(['get', 'post'], 'webhook-subscription/{provider}', 'SubscriptionController@handleSubscriptionWebhook');
    Route::match(['get', 'post'], 'force-subscription', 'SubscriptionController@forceSubscription')->name('force.subscription');
});
