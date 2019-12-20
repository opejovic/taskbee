<?php

namespace taskbee\Billing;

use taskbee\Models\Plan;

class StripePlansGateway
{
    /**
     * Stripe's API key.
     *
     * @var string
     */
    private $apiKey;

    /**
     * StripePlansGateway constructor.
     *
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = \Stripe\Stripe::setApiKey($apiKey);
    }

    /**
     * Generates the product and its subscription plans in Stripe and in our database.
     *
     */
    public function generate()
    {
        Plan::assemble($this->plans());
    }

    /**
     * Returns the collection of created Stripe plans.
     *
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Illuminate\Support\Collection
     */
    public function plans()
    {
        $product = $this->product();

        return collect([
            $this->basic($product),
            $this->standard($product),
            $this->premium($product),
        ]);
    }

    /**
     * Create a StripeProduct
     *
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Stripe\Product
     */
    public function product()
    {
        return \Stripe\Product::create([
            "name"                 => "TaskBee Workspace Bundle",
            "statement_descriptor" => "TaskBee Workspace",
            "type"                 => "service",
        ]);
    }

    /**
     * Create a StripePlan
     *
     * @param  $nickname
     * @param  $amount
     * @param  $members_limit
     * @param  $product
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Stripe\Plan
     */
    public function plan($nickname, $amount, $members_limit, $product)
    {
        return \Stripe\Plan::create([
            "nickname"       => $nickname,
            "amount"         => $amount,
            "billing_scheme" => "per_unit",
            "interval"       => "month",
            "currency"       => "eur",
            "usage_type"     => "licensed",
            "metadata"       => ["members_limit" => $members_limit],
            "product"        => $product['id'],
        ]);
    }

    /**
     * Create a Basic Monthly StripePlan.P
     *
     * @param  \Stripe\Product $product
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Stripe\Plan
     */
    public function basic($product)
    {
        return $this->plan(
            Plan::BASIC,
            Plan::BASIC_PRICE,
            Plan::BASIC_MEMBERS_LIMIT,
            $product
        );
    }

    /**
     * Create a Standard Monthly StripePlan.
     *
     * @param  \Stripe\Product $product
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Stripe\Plan
     */
    public function standard($product)
    {
        return $this->plan(
            Plan::STANDARD,
            Plan::STANDARD_PRICE,
            Plan::STANDARD_MEMBERS_LIMIT,
            $product
        );
    }

    /**
     * Create a Premium Monthly StripePlan.
     *
     * @param  \Stripe\Product $product
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Stripe\Plan
     */
    public function premium($product)
    {
        return $this->plan(
            Plan::PREMIUM,
            Plan::PREMIUM_PRICE,
            Plan::PREMIUM_MEMBERS_LIMIT,
            $product
        );
    }
}
