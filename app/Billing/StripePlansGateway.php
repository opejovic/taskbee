<?php

namespace taskbee\Billing;

use taskbee\Models\Plan;

class StripePlansGateway
{
    private $apiKey;

    /**
     * StripePlansGateway constructor.
     *
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
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
     * @return \Stripe\Product
     */
    public function product()
    {
        return \Stripe\Product::create([
            "name"                 => 'Workspace Bundle',
            "statement_descriptor" => 'TaskMonkey Workspace',
            "type"                 => "service",
        ], ['api_key' => $this->apiKey]);
    }

    /**
     * Create a StripePlan
     *
     * @param $nickname
     * @param integer $amount
     * @param integer $members_limit
     * @param $product
     *
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
        ], ['api_key' => $this->apiKey]);
    }

    /**
     * Create a Basic Monthly StripePlan.
     *
     * @param \Stripe\Product $product
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
     * @param \Stripe\Product $product
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
     * @param \Stripe\Product $product
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
