# Models:
    - Product (Bundle) (Product objects describe items that your customers can subscribe to with a Subscription. An associated Plan determines the product pricing.)

    - Plan (Plan determines the product pricing / e.g. $35 / month)

    - Subscription ()

    - Bundle (This is our product, describe items that your customers can subscribe to with a Subscription) (Basic, Advanced, Pro Workspace)

    - Plan (This is the payment plan)
    
    - Subscription

# TESTS:

Customer buys a subscription for an offered bundle
If the payment is successful, customer is redirected to workspace creation page where he first creates an account, then a workspace (provides a name), and then he invites some team members.

- customers_can_subscribe_for_a_bundle
$this->app->singleton(StripeProduct::class);
$this->app->singleton(StripePlan::class);
