<?php

namespace App\Models;

use App\Exceptions\SubscriptionExistsException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    /**
     * Class constants.
     *
     */
    const BASIC = 'Basic Workspace Bundle';
    const STANDARD = 'Standard Worksspace Bundle';
    const PREMIUM = 'Premium Workspace Bundle';
    const BASIC_PRICE = 3995;
    const STANDARD_PRICE = 6995;
    const PREMIUM_PRICE = 9995;

    /**
     * Attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Bundle has many subscriptions.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Does subscription for the given email exists?
     *
     * @param $email
     * @return bool
     */
    public function hasActiveSubscriptionFor($email)
    {
        return $this->subscriptions()->where('email', $email)->exists();
    }
}
