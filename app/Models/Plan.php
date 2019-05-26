<?php

namespace App\Models;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    /**
     * Class constants.
     *
     */
    const BASIC = 'Basic Monthly';
    const STANDARD = 'Standard Monthly';
    const PREMIUM = 'Premium Monthly';
    const PER_USER = 'Per User Monthly';
    const BASIC_PRICE = 3995;
    const STANDARD_PRICE = 6995;
    const PREMIUM_PRICE = 9995;
    const PER_USER_PRICE = 799;
    const BASIC_MEMBERS_LIMIT = 5;
    const STANDARD_MEMBERS_LIMIT = 10;
    const PREMIUM_MEMBERS_LIMIT = 15;
    const PER_USER_LIMIT = 1;


    protected $guarded = [];

    /**
     * Saves given plans from the array to db.
     *
     * @param array $data
     * @return void
     * @author 
     */
    public static function assemble($data)
    {
        $plans = $data->map(function ($plans) {
            return [
                'name' => $plans['nickname'],
                'amount' => $plans['amount'],
                'interval' => $plans['interval'],
                'members_limit' => $plans['metadata']['members_limit'],
                'currency' => $plans['currency'],
                'stripe_id' => $plans['id'],
            ]; 
        })->toArray();

        self::insert($plans);
    }

    /**
     * Plan belongs to a Bundle.
     *
     * @return App\Models\Bundle
     */
    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'product', 'stripe_id');
    }

    /**
     * Plan has many subscriptions
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id', 'stripe_id');
    }

    /**
     * Purchase the subscription plan.
     *
     * @param $email
     * @param $token
     * @param App\Billing\StripeSubscriptionGateway $subscriptionGateway
     * @return App\Models\Subscription
     */
    public function purchase($email, $token, $subscriptionGateway)
    {
        $sub = $subscriptionGateway->subscribe($email, $token, $this);

        return $this->subscriptions()->create([
            'stripe_id' => $sub['id'],
            'bundle_id' => $this->product,
            'bundle_name' => $this->product,
            'customer' => $sub['customer'],
            'email' => $email,
            'billing' => "charge_automatically",
            'amount' => $sub['plan']['amount'],
            'status' => $sub['status'],
            'start_date' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonth(),
        ]);
    }
}
