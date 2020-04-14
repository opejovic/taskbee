# Installation

1. `cp .env.example .env`
2. `composer install`
3. `npm install`
4. `npm run dev` (compiles the assets)
5. `php artisan migrate`
6. `php artisan key:generate`
7. Delete all test data from stripe connected to the TaskBee app.
    - You can delete the data and web hooks manually on Stripe Dashboard or
    - Run `php artisan clear:stripe-data` (if you run this command, you should also run `php artisan migrate:fresh`, to clear your DB) && `php artisan clear:stripe-webhook` - This will clear Stripe Products and Webhooks
8. Setup NGROK - ./ngrok http http://localhost (change the localhost with your app dev url)
    - paste in the https://xxxxxxxx.ngrok.io into .env (**https** not http)
9. `php artisan generate:plans`
10. `php artisan generate:stripe-webhook`
11. paste whsec\_.... from stripe webhook secret into env STRIPE_WEBHOOK_SECRET
12. `php artisan serve` (if you are not using valet, otherwise no need to run this command)
13. Run queue worker (`php artisan queue:work` or `php artisan queue:listen`)
14. Use Stripe's testing card 4242 4242 4242 4242 for subscribing.
15. Start assigning tasks! :)

General css style should be placed in .scss files living in resources/sass
If adding a new css/js file, ensure that it is referenced in webpack.mix.js. Run `npm run dev` to recompile your assets.

Run `npm run watch` to monitor and auto apply any changes to css/js files.
