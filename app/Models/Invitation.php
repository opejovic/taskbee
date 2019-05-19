<?php

namespace App\Models;

use App\Mail\SubscriptionPurchaseEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Invitation extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Send the SubscriptionPurchaseEmail to the user that purchased subscription.
     *
     * @return void
     */
    public function send($subscription)
    {
        Mail::to($this->email)->queue(new SubscriptionPurchaseEmail($subscription, $this));
    }
}
