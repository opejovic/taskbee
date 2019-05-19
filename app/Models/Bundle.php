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
    const ADVANCED = 'Advanced Worksspace Bundle';
    const PRO = 'Pro Workspace Bundle';
    const BASIC_PRICE = 3995;
    const ADVANCED_PRICE = 6995;
    const PRO_PRICE = 9995;

    /**
     * Attributes that are not mass assignable.
     *
     */
    protected $guarded = [];

    // *
    //  * Get the route key for the model.
    //  *
    //  * @return string
     
    // public function getRouteKeyName()
    // {
    //     return 'name';
    // }

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
