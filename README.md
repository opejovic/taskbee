# Installation

1) `cp .env.example .env`
2) `composer install`
3) `npm install`
4) `npm run dev` (compiles the assets)
5) `php artisan migrate`
6) Delete all test data from stripe, including the created webhooks
7) Setup NGROK - ./ngrok http http://localhost
    - paste in the https://xxxxxxxx.ngrok.io into .env (https not http)
8) `php artisan generate-plans`
9) `php artisan stripe-webhook`
10) paste whsec_.... from stripe webhook secret into env STRIPE_WEBHOOK_SECRET
11) `php artisan serve`
12) Run queue worker (`php artisan queue:work` or `php artisan queue:listen`)
13) Use Stripe's testing card 4242 4242 4242 4242 for subscribing.

General css style should be placed in .scss files living in resources/sass
If adding a new css/js file, ensure that it is referenced in webpack.mix.js. Run `npm run dev` to recompile your assets.

Run `npm run watch` to monitor and auto apply any changes to css/js files.
