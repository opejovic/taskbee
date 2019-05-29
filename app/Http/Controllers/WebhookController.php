<?php

namespace App\Http\Controllers;

use App\Billing\StripeSubscriptionGateway;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * summary
     *
     * @return void
     * @author 
     */
    public function checkout()
    {
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = config('services.stripe.webhook.secret');

        $payload = @file_get_contents('php://input');
        $sig_header = request()->server('HTTP_STRIPE_SIGNATURE');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400); // PHP 5.4 or greater
            exit();
          } catch(\Stripe\Error\SignatureVerification $e) {
            // Invalid signature
            http_response_code(400); // PHP 5.4 or greater
            exit();
        }

        switch ($event->type) {
            // ... handle the checkout.session.completed event
            case 'checkout.session.completed':
                $session = $event->data->object; // contains a StripeSession
                
                $subscriptionGateway = new StripeSubscriptionGateway(
                    \Stripe\Stripe::setApiKey(config('services.stripe.secret'))
                );
                $subscriptionGateway->fulfill($session);
                break;
            
            // // ... handle the payment_intent.succeeded event
            // case 'payment_intent.succeeded':
            //     $paymentIntent = $event->data->object; // contains a StripePaymentIntent
            //     http_response_code(200);
            //     break;

            // // ... handle the charge.succeeded event
            // case 'charge.succeeded':
            //     $chargeSucceeded = $event->data->object; // contains a StripeCharge
            //     http_response_code(200);
            //     break;

            // ... handle other event types
            default:
                // Unexpected event type
                http_response_code(400);
                exit();
        }

        http_response_code(200); // PHP 5.4 or greater
    }
}
