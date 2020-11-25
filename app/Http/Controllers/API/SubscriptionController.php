<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Services\Subscription\SubscriptionService;
use App\Services\Webhook\WebhookHandlerService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Handles webhook data;
     *
     * @param Request $request
     * @param WebhookHandlerService $weebhookHandler
     * @return void
     */
    public function handleSubscriptionWebhook(Request $request, WebhookHandlerService $weebhookHandler, string $provider)
    {
        $request = $this->generateDummyData($request, $provider);

        $weebhookHandler->handleSubscriptionWebhook($request);

        return 'OK';
    }

    /**
     * Handles webhook data;
     *
     * @param Request $request
     * @param WebhookHandlerService $weebhookHandler
     * @return void
     */
    public function forceSubscription(Request $request, SubscriptionService $subscriptionService)
    {
        $user = User::where('email', $request->email)->first();

        $subscriptionService->initiateSubscription([], $user);

        return 'OK';
    }

    /**
     * TODO: Method only for testing purposes;
     * Generate dummy webhook data;
     *
     * @param Request $request
     * @return Request
     */
    protected function generateDummyData(Request $request, $provider) : Request
    {
        $webhookData = $this->generateAppleWebhook($request);

        $request->request->add(['body' => $webhookData, 'provider' => $provider]);

        return $request;
    }

    /**
     * TODO: Method only for testing purposes;
     * Generate Apple webhook dummy data;
     *
     * @param Request $request
     * @return array
     */
    protected function generateAppleWebhook(Request $request) : array
    {
        $appleNotificationTypes = ['CANCEL', 'DID_RENEW', 'INITIAL_BUY', 'DID_FAIL_TO_RENEW'];

        $notificationKey = array_rand($appleNotificationTypes);

        $notificationType = $appleNotificationTypes[$notificationKey];
        $notificationType = 'INITIAL_BUY';

        $appleWebhookData = [
            'notification_type' => $notificationType,

            // INITIAL_BUY
            'auto_renew_adam_id' => 'UNIQUEAPPLECODE',
            'latest_receipt' => 'kfldpEkfilsCd',
            'customer_email' => 'user2@test.lt',


            // CANCEL
            'cancellation_date' => date("Y-m-d H:i:s", strtotime('+0 hours')),


            // DID_RENEW

            // DID_FAIL_TO_RENEW
            'is_in_billing_retry_period' => 1,
            'grace_period_expires' => date("Y-m-d H:i:s", strtotime('1 month')),
        ];

        return $appleWebhookData;
    }
}
