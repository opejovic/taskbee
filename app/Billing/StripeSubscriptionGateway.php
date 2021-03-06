<?php

namespace taskbee\Billing;

use taskbee\Models\Customer;
use taskbee\Models\Subscription;
use taskbee\Models\Workspace;

class StripeSubscriptionGateway implements SubscriptionGateway
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Fulfills the process of subscribing the customer after a successful payment.
     *
     * @param  array $subscription
     * @throws \Stripe\Exception\ApiErrorException
     * @return void
     */
    public function fulfill($subscription) : void
    {
        if ($this->subscriptionPaid($subscription)) {
            $this->setupSubscription($subscription);
        }
    }

    /**
     * Is subscription paid for?
     *
     * @param  $subscription
     * @throws \Stripe\Exception\ApiErrorException
     * @return bool
     */
    public function subscriptionPaid($subscription) : bool
    {
        return \Stripe\Subscription::retrieve([
            'id'     => $subscription->id,
            'expand' => ['latest_invoice']
        ])['latest_invoice']['paid'];
    }

    /**
     * Store subscription in the database.
     *
     * @param $subscription
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function setupSubscription($subscription) : void
    {
        $customer = \Stripe\Customer::retrieve($subscription->customer);

        # If the customer with the given email doesnt exist, create it.
        if (! Customer::where('email', $customer['email'])->exists()) {
            Customer::create([
                'email'     => $customer['email'],
                'stripe_id' => $customer['id'],
            ]);
        }

        Subscription::buildFrom(
            \Stripe\Subscription::retrieve($subscription->id),
            $customer['email']
        );
    }

    /**
     * Handles the invoice payment succeeded event.
     *
     * @param $invoice
     * @return void
     */
    public function handleInvoice($invoice) : void
    {
        switch ($invoice->description) {
            # ... handle the Add additional member slot payment
            case 'Add additional member slot':
                Workspace::addSlot($invoice->subscription);
                break;

            # ... handle the invoice.payment_succeeded event for subscription renewal
            case 'Renew subscription':
                Subscription::renew($invoice->subscription);
                break;

            default:
                # ... unexpected event type
                exit();
        }

        # Add additional checks for the paid invoice
        # need to notify the customer that he can now invite additional member.
    }

    /**
     * Increase the subscriptions member slots.
     *
     * @param $workspace
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Stripe\Invoice
     */
    public function increaseSlot($workspace) : \Stripe\Invoice
    {
        # Retrieve subscription
        $stripeSub = \Stripe\Subscription::retrieve($workspace->subscription->stripe_id);

        # Increment current stripe subscription item (current subscription plan) quantity.
        \Stripe\Subscription::update($stripeSub['id'], [
            'cancel_at_period_end' => false,
            'items' => [
                [
                    'id' => $stripeSub['items']['data'][0]['id'],
                    'quantity' => $stripeSub['quantity'] + 1,
                ],
            ],
        ]);

        # Create an invoice for the increased member slot.
        return $this->createInvoice($stripeSub);
    }

    /**
     * Invoice the additional member slot.
     *
     * @param $stripeSub
     * @throws \Stripe\Exception\ApiErrorException
     * @return \Stripe\Invoice
     */
    public function createInvoice($stripeSub) : \Stripe\Invoice
    {
        # Create and pay an invoice for added member - make this a first step before editing a subscription
        return \Stripe\Invoice::create([
            "customer" => $stripeSub['customer'],
            "subscription" => $stripeSub['id'],
            "collection_method" => "send_invoice",
            "days_until_due" => 1,
            "description" => 'Add additional member slot'
        ])->sendInvoice();
    }

    /**
     * Inspects the subscription status, after the subscription updated event,
     * and updates subscription locally.
     *
     * @param  $subscription
     * @return void
     */
    public function inspect($subscription) : void
    {
        if (collect([
            Subscription::PAST_DUE,
            Subscription::UNPAID,
            Subscription::CANCELED
        ])->contains($subscription->status)) {
            Subscription::expire($subscription);
        }
    }
}
