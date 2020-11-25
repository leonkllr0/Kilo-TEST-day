<?php

namespace App\Services\Webhook;

use Illuminate\Http\Request;
use App\Services\Subscription\SubscriptionService;

interface BaseSubscriptionWebhookServiceInterface
{
    public function __construct(SubscriptionService $subscriptionService);

    /**
     * Function will determine which method of webhook handler use by webhook content;
     *
     * @param Request $request
     * @return void
     */
    public function methodSelector(Request $request);

    /**
     * Handle all events dependent on new subscription creation;
     *
     * @param Request $request
     * @return void
     */
    public function parseSubscriptionInitiation(Request $request);

    /**
     * Handle all events debendent on user successfully renewed subscription;
     *
     * @param Request $request
     * @return void
     */
    public function parseSubscriptionRenewed(Request $request);

    /**
     * Handle all events on user failed to renew subscription;
     *
     * @param Request $request
     * @return void
     */
    public function parseSubscriptionUnsuccessfulRenewal(Request $request);

    /**
     * Handle events when user cancels subscription;
     *
     * @param Request $request
     * @return void
     */
    public function parseSubscriptionCanceled(Request $request);
}
