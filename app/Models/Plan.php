<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
	/**
	 * Class constants.
	 *
	 */
	const BASIC               = 'Basic Monthly';
	const BASIC_PRICE         = 598; // per user
	const BASIC_MEMBERS_LIMIT = 5;

	const STANDARD               = 'Standard Monthly';
	const STANDARD_PRICE         = 399;
	const STANDARD_MEMBERS_LIMIT = 10;

	const PREMIUM               = 'Premium Monthly';
	const PREMIUM_PRICE         = 399;
	const PREMIUM_MEMBERS_LIMIT = 15;


	/**
	 * Attributes that are not mass-assignable.
	 *
	 */
	protected $guarded = [];

	/**
	 * Saves given plans from the array to db.
	 *
	 * @param array $data
	 *
	 * @return void
	 * @author
	 */
	public static function assemble($data)
	{
		$data->map(function ($plans) {
			return [
				'name'          => $plans['nickname'],
				'amount'        => $plans['amount'],
				'interval'      => $plans['interval'],
				'members_limit' => $plans['metadata']['members_limit'],
				'currency'      => $plans['currency'],
				'stripe_id'     => $plans['id'],
			];
		})->each(function ($plan) {
			self::create($plan);
		});
	}

	/**
	 * Plan has many subscriptions
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subscriptions()
	{
		return $this->hasMany(Subscription::class, 'plan_id', 'stripe_id');
	}

	/**
	 * Get the amount in euros.
	 *
	 * @return int
	 */
	public function getAmountInEurAttribute()
	{
		return number_format(($this->amount * $this->members_limit / 100), 2);
	}
}
