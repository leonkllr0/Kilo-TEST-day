<?php

namespace App\Services\Webhook;

use Illuminate\Http\Request;
use App\Services\Subscription\SubscriptionService;
use App\Services\Webhook\AppleSubscriptionWebhookService;
use App\Services\Webhook\StripeSubscriptionWebhookService;

class WebhookHandlerService
{
    /**
     * This function implements selection of correct payment provider;
     * All logic for now is simplified;
     *
     * @param Request $request
     * @return void
     */
    public function handleSubscriptionWebhook(Request $request)
    {
        echo '<br> Select payment provider by provider: ' . $request->provider;
        logger('Select payment provider by provider: ' . $request->provider);

        switch ($request->provider) {
            case 'apple':
                (new AppleSubscriptionWebhookService(new SubscriptionService))->methodSelector($request);
                break;
            case 'stripe':
                (new StripeSubscriptionWebhookService(new SubscriptionService))->methodSelector($request);
                break;

            default:
                echo '<br> Payment provider not found:' . $request->provider;
                logger('Payment provider not found:' . $request->provider);
                break;
        }
    }
}
