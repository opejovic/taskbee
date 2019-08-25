# Installation

cp .env.example .env
composer install
npm install
npm run dev (compiles the assets)
run migration
php artisan serve
Or


General css style should be placed in .scss files living in resources/sass
If adding a new css/js file, ensure that it is referenced in webpack.mix.js. Run 'npm run dev' to recompile your assets.

Run 'npm run watch' to monitor and auto apply any changes to css/js files.
