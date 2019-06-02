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
    const BASE_FEE = 1000;

    const BASIC = 'Basic Monthly';
    const BASIC_PRICE = 799; // per user
    const BASIC_MEMBERS_LIMIT = 5;
    
    const STANDARD = 'Standard Monthly';
    const STANDARD_PRICE = 699;
    const STANDARD_MEMBERS_LIMIT = 10;
    
    const PREMIUM = 'Premium Monthly';
    const PREMIUM_PRICE = 599;
    const PREMIUM_MEMBERS_LIMIT = 15;
    
    const PER_USER = 'Per User Monthly';
    const PER_USER_PRICE = 799;
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
        $data->map(function ($plans) {
            return [
                'name' => $plans['nickname'],
                'amount' => $plans['amount'],
                'interval' => $plans['interval'],
                'members_limit' => $plans['metadata']['members_limit'],
                'currency' => $plans['currency'],
                'stripe_id' => $plans['id'],
            ]; 
        })->each(function ($plan) { 
            self::create($plan); 
        });
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
     * summary
     *
     * @return void
     * @author 
     */
    public function getAmountInEurAttribute()
    {
        return number_format($this->ammount / 100);
    }
}
