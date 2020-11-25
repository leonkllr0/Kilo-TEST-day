<?php

namespace App\Services\Webhook;

use Illuminate\Http\Request;
use App\Services\Subscription\SubscriptionService;

class StripeSubscriptionWebhookService implements BaseSubscriptionWebhookServiceInterface
{
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * {@inheritDoc}
     *
     * Method will help to decide which webhook handler method we will use for this request;
     *
     * @param Request $request
     * @return void
     */
    public function methodSelector(Request $request)
    {
        $notificationType = $request->body['notification_type'];

        echo '<br> Select correct method to handle request for type: ' . $notificationType;
        logger('Select correct method to handle request for type: ' . $notificationType);

        switch ($notificationType) {
            default:
                echo 'Notification type not found: ' . $notificationType;
                logger('Notification type not found:' . $notificationType);
                break;
        }
    }

    /**
     * {@inheritDoc}
     *
     * Implementation of Apple store webhook data handler for newly created subscription;
     *
     * @param Request $request
     * @return void
     */
    public function parseSubscriptionInitiation(Request $request)
    {
        echo '<br> Subscription initiated parse logic';
        logger('Subscription initiated parse logic');
    }

    /**
     * {@inheritDoc}
     *
     * Apple implementation on subscription renewal;
     *
     * @param Request $request
     * @return void
     */
    public function parseSubscriptionRenewed(Request $request)
    {
        echo '<br> Subscription renewed parse logic';
        logger('Subscription renewed parse logic');
    }

    /**
     * {@inheritDoc}
     *
     * Apple implementation on unsuccesfull renewal;
     *
     * @param Request $request
     * @return void
     */
    public function parseSubscriptionUnsuccessfulRenewal(Request $request)
    {
        echo '<br> Subscription unsuccessful renewal parse logic';
        logger('Subscription unsuccessful renewal parse logic');
    }

    /**
     * {@inheritDoc}
     *
     * Apple implementation on subscription cancelation;
     *
     * @param Request $request
     * @return void
     */
    public function parseSubscriptionCanceled(Request $request)
    {
        echo '<br> Subscription canceled parse logic';
        logger('Subscription canceled parse logic');
    }
}
