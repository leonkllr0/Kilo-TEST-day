<?php

namespace App\Services\Webhook;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Services\Subscription\SubscriptionService;

class AppleSubscriptionWebhookService implements BaseSubscriptionWebhookServiceInterface
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
            case 'INITIAL_BUY':
                $this->parseSubscriptionInitiation($request);
                break;
            case 'DID_RENEW':
                $this->parseSubscriptionRenewed($request);
                break;
            case 'DID_FAIL_TO_RENEW':
                $this->parseSubscriptionUnsuccessfulRenewal($request);
                break;
            case 'CANCEL':
                $this->parseSubscriptionCanceled($request);
                break;

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
        logger('Subscription initiated parse logic');

        $data = [
            'code' => $request->body['auto_renew_adam_id'], // Unique subscription code to find which subscription we want to extend;
            'provider_name' => 'apple',
            'latest_receipt' => $request->body['latest_receipt'],
            'initiated_at' => $request->body['initiated_at'] ?? date("Y-m-d H:i:s", strtotime('+0 hours')),
            'subscription_valid_until' => date("Y-m-d H:i:s", strtotime('+' . config('app.subscriptionPeriod'))),
        ];

        $user = User::where('email', $request->body['customer_email'])->first();

        // ! Error handler goes here;

        $this->subscriptionService->initiateSubscription($data, $user);
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
        logger('Subscription renewed parse logic');

        $data = [
            'latest_receipt' => $request->body['latest_receipt'],
            'renewed_at' => $request->body['initiated_at'] ?? date("Y-m-d H:i:s", strtotime('+0 hours')),
            'subscription_valid_until' => date("Y-m-d H:i:s", strtotime('+' . config('app.subscriptionPeriod'))),
        ];

        $userSubscription = UserSubscription::where('code', $request->body['auto_renew_adam_id'] ?? null)->first();

        // ! Error handler goes here;

        $this->subscriptionService->renewSubscription($data, $userSubscription);
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
        logger('Subscription unsuccessful renewal parse logic');

        $data = [
            'is_in_billing_retry_period' => $request->body['is_in_billing_retry_period'] ?? 0,
            'failed_to_renew_at' => $request->body['failed_to_renew_at'] ?? date("Y-m-d H:i:s", strtotime('+0 hours')),
        ];

        $userSubscription = UserSubscription::where('code', $request->body['auto_renew_adam_id'] ?? null)->first();

        // ! Error handler goes here;

        $this->subscriptionService->subscriptionUnsuccessfulRenewal($data, $userSubscription);
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
        logger('Subscription canceled parse logic');

        $data = [
            'canceled_at' => $request->body['cancellation_date'] ?? date("Y-m-d H:i:s", strtotime('+0 hours')),
        ];

        $userSubscription = UserSubscription::where('code', $request->body['auto_renew_adam_id'] ?? null)->first();

        // ! Error handler goes here;

        $this->subscriptionService->cancelSubscription($data, $userSubscription);
    }
}
