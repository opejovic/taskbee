<?php

namespace App\Models;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = [];

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'product', 'stripe_id');
    }

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id', 'stripe_id');
    }

    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function purchase($subscriptionGateway, $customer)
    {
        $sub = $subscriptionGateway->createSubscriptionFor($customer, $this);

        return Subscription::create([
            'stripe_id' => $sub['id'],
            'bundle_id' => $this->product,
            'bundle' => $this->product,
            'customer' => $customer->id,
            'email' => $customer->email,
            'billing' => "charge_automatically",
            'plan_id' => $this->stripe_id,
            'amount' => $sub['plan']['amount'],
            'status' => $sub['status'],
            'start_date' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonth(),
        ]);
    }
}
