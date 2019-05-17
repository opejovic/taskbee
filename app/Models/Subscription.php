<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * Attributes that are not mass assignable.
     *
     */
    protected $guarded = [];

    /**
     * Cancel the subscription.
     *
     * @return void
     */
    public function cancel()
    {
    	$this->delete();
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
            'bundle' => $this->bundle,
            'amount' => $this->amount,
        ];
    }
}
