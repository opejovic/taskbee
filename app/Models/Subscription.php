<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * Attributes that are not mass assignable.
     *
     */
    protected $guarded = [];

    /**
     * Get the invitation associated with the subscription.
     */
    public function invitation()
    {
        return $this->hasOne(Invitation::class, 'subscription_id');
    }

    /**
     * Cancel the subscription.
     *
     * @return void
     */
    public function cancel()
    {
    	$this->update([
            'cancelled_at' => Carbon::now(),
            'status' => 'cancelled', 
        ]);
    }

    /**
     * Cast subscription to array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'email' => $this->email,
            'bundle_name' => $this->bundle_name,
            'amount' => $this->amount,
            'status' => $this->status,
            'start_date' => $this->start_date->format('Y-m-d'),
            'expires_at' => $this->expires_at->format('Y-m-d'),
        ];
    }
}
