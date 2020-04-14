<?php

namespace taskbee\Billing;

use taskbee\Billing\StripeSubscriptionGateway;

class StripeWebhookGateway
{
    /**
     * The stripe api key.
     */
    protected $apiKey;

    /**
     * The StripeWebhookGateway constructor.
     */
    public function __construct()
    {
        # Set your secret key: remember to change this to your live secret key in production
        # See your keys here: https://dashboard.stripe.com/account/apikeys
        $this->apiKey = \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Handle stripe webhooks.
     *
     * @throws \Stripe\Exception\ApiErrorException
     * @throws \Stripe\Exception\SignatureVerificationException
     * @return void
     */
    public function handleEvents() : void
    {
        $event = $this->getEvent();

        $subscriptionGateway = new StripeSubscriptionGateway();

        switch ($event->type) {
            # ... handle the customer.subscription.created event
            case 'customer.subscription.created':
                $subscription = $event->data->object; // contains a StripeSession
                $subscriptionGateway->fulfill($subscription);
                http_response_code(200);
                break;

            # ... handle the invoice.payment_succeeded event
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object; // contains a StripePaymentIntent
                $subscriptionGateway->handleInvoice($invoice);
                http_response_code(200);
                break;

            # ... handle the subscription updated event
            case 'customer.subscription.updated':
                $subscription = $event->data->object; # contains a StripePaymentIntent
                # if subscription status is unpaid - lock the workspace access until its paid
                $subscriptionGateway->inspect($subscription);
                http_response_code(200);
                break;

            # ... handle the subscription deleted event
            case 'customer.subscription.deleted':
                $subscription = $event->data->object; # contains a StripePaymentIntent
                # if subscription status is deleted - lock the workspace access
                # @TODO Make a new method on subGateway - cancel().
                $subscriptionGateway->inspect($subscription);
                http_response_code(200);
                break;

            # ... handle the checkout.session.completed event
            case 'checkout.session.completed':
                $session = $event->data->object; # contains a StripeSession
                http_response_code(200);
                break;

            # ... handle other event types
            default:
                # Unexpected event type
                http_response_code(400);
                exit();
        }

        http_response_code(200); # PHP 5.4 or greater
    }

    /**
    * @throws \Stripe\Exception\SignatureVerificationException
    * @return \Stripe\Event|null
    */
    protected function getEvent() : \Stripe\Event
    {
        # You can find your endpoint's secret in your webhook settings
        $endpoint_secret = config('services.stripe.webhook.secret');

        $payload = @file_get_contents('php://input');
        $sig_header = request()->server('HTTP_STRIPE_SIGNATURE');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            # Invalid payload
            http_response_code(400); // PHP 5.4 or greater
            exit();
        } catch (\Stripe\Error\SignatureVerification $e) {
            # Invalid signature
            http_response_code(400); // PHP 5.4 or greater
            exit();
        }

        return $event;
    }
}
