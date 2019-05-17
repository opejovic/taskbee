<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Bundle::class)->states('basic')->create();
        factory(App\Models\Bundle::class)->states('advanced')->create();
        factory(App\Models\Bundle::class)->states('pro')->create();
    }
}
