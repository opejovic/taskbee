<?php

namespace taskbee\Http\Controllers;

use taskbee\Billing\StripeSubscriptionGateway;

class WebhookController extends Controller
{
    /**
     * Handle stripe web hooks.
     */
    public function handle()
    {
        # Set your secret key: remember to change this to your live secret key in production
        # See your keys here: https://dashboard.stripe.com/account/apikeys
        $apiKey = \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

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
                # if subscription status is unpaid - lock the workspace access untill its paid
                $subscriptionGateway->inspect($subscription);
                http_response_code(200);
                break;

            # ... handle the subscription deleted event
            case 'customer.subscription.deleted':
                $subscription = $event->data->object; # contains a StripePaymentIntent
                # if subscription status is deleted - lock the workspace access
                # -- Make a new method on subGateway - cancel().
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
}
