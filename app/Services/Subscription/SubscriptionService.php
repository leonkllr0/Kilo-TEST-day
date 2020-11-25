<?php

namespace App\Services\Subscription;

use App\Models\User;
use App\Models\UserSubscription;
use App\Models\UserSubscriptionReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionService
{
    /**
     * Handle all events dependent on new subscription creation;
     *
     * @param array $data
     * @param User $user
     * @return void
     */
    public function initiateSubscription(array $data, User $user)
    {
        $insertData['user_id'] = $user->id;
        $insertData['code'] = $data['code'] ?? null;
        $insertData['latest_receipt'] = $data['latest_receipt'] ?? null;
        $insertData['provider_name'] = $data['provider_name'] ?? null;
        $insertData['initiated_at'] = $data['initiated_at'] ?? date("Y-m-d H:i:s", strtotime('+0 hours'));
        $insertData['subscription_valid_until'] = $data['subscription_valid_until'] ?? date("Y-m-d H:i:s", strtotime('+' . config('app.subscriptionPeriod')));

        logger('Initiate subscription!');

        // TODO: ! Must be imlemented logic for case if user subscription was activated manually, and after manually activation user boughts subscription;
        // TODO: ! In that case subscription line must be found and edited during purchase process;

        // TODO: ! Validator goes here;
        //Validator::make($insertData, $user->subscriptionInitiationRules())->validate();
        $validator = Validator::make($insertData, $user->subscriptionInitiationRules());

        if ($validator->fails()) {
            dd($validator->messages());
        }

        $subscription = UserSubscription::create($insertData);

        if ($insertData['latest_receipt']) {
            UserSubscriptionReceipt::create(['subscription_id' => $subscription->id, 'receipt_code' => $insertData['latest_receipt']]);
        }

        // TODO: ! Send notification to user;
    }

    /**
     * Handle all events debendent on user successfully renewed subscription;
     *
     * @param array $data
     * @param UserSubscription $userSubscription
     * @return void
     */
    public function renewSubscription(array $data, UserSubscription $userSubscription)
    {
        $insertData['canceled_at'] = null;
        $insertData['latest_receipt'] = $data['latest_receipt'] ?? null;
        $insertData['renewed_at'] = $data['renewed_at'] ?? date("Y-m-d H:i:s", strtotime('+0 hours'));
        $insertData['subscription_valid_until'] = $data['subscription_valid_until'] ?? date("Y-m-d H:i:s", strtotime('+' . config('app.subscriptionPeriod')));

        logger('Renewed subscription!');

        // TODO: ! Validator goes here;

        $userSubscription->update($insertData);

        if ($insertData['latest_receipt']) {
            UserSubscriptionReceipt::create(['subscription_id' => $userSubscription->id, 'receipt_code' => $insertData['latest_receipt']]);
        }
    }

    /**
     * Handle all events on user failed to renew subscription;
     *
     * @param array $data
     * @param UserSubscription $userSubscription
     * @return void
     */
    public function subscriptionUnsuccessfulRenewal(array $data, UserSubscription $userSubscription)
    {
        $insertData['is_in_billing_retry_period'] = $data['is_in_billing_retry_period'] ?? 0;
        $insertData['failed_to_renew_at'] = $data['failed_to_renew_at'] ?? date("Y-m-d H:i:s", strtotime('+0 hours'));

        if ($insertData['is_in_billing_retry_period'] == 0) {
            $insertData['canceled_at'] = date("Y-m-d H:i:s", strtotime('+0 hours'));
        }

        // TODO: ! Implement functionality to calculate subscription valid period for case when subscription not canceled, but failed to renew;
        $insertData['subscription_valid_until'] = date("Y-m-d H:i:s", strtotime('+' . config('app.subscriptionValidPeriodAfterFailedRenew')));

        logger('Failed renewal subscription!');

        // TODO: ! Validator goes here;

        $userSubscription->update($insertData);
    }

    /**
     * Handle events when user cancels subscription;
     *
     * @param array $data
     * @param UserSubscription $userSubscription
     * @return void
     */
    public function cancelSubscription(array $data, UserSubscription $userSubscription)
    {
        $insertData['canceled_at'] = $data['canceled_at'] ?? date("Y-m-d H:i:s", strtotime('+0 hours'));

        logger('Cancel subscription!');

        // TODO: ! Validator goes here;

        $userSubscription->update($insertData);
    }
}
