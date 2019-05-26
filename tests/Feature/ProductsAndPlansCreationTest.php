<?php

namespace Tests\Feature;

use App\Models\Bundle;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsAndPlansCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function initial_product_and_plans_creation()
    {
        // Arrange: define plans for product
        $plans = [
            'basic' => [
                'name' => 'Basic Monthly',
                'members_limit' => 5,
                'price' => 3995,
            ],

            'standard' => [
                'name' => 'Standard Monthly',
                'members_limit' => 10,
                'price' => 6995,
            ],

            'premium' => [
                'name' => 'Premium Monthly',
                'members_limit' => 15,
                'price' => 9995,            
            ],

            'per_user' => [
                'name' => 'Per User Monthly',
                'members_limit' => 15,
                'price' => 9995,            
            ],
        ];

        // Act: type console command (generate-plans)
        $created_at = Carbon::now()->unix();
        $this->artisan('generate-plans');

        // Assert: the subscription plans exist in the db and on the stripe server.
        // $this->assertCount(4, Plan::all());
        // $this->assertNotNull($basicPlan = Plan::whereName('Basic Monthly')->first());
        // $this->assertNotNull($standardPlan = Plan::whereName('Standard Monthly')->first());
        // $this->assertNotNull($premiumPlan = Plan::whereName('Premium Monthly')->first());
        // $this->assertNotNull($perUserPlan = Plan::whereName('Per User Monthly')->first());

        $stripePlans = \Stripe\Plan::all([
            "limit" => 10,
            "created" => [
                "gte" => $created_at,
            ],
        ], ['api_key' => config('services.stripe.secret')]);

        $this->assertCount(4, $stripePlans['data']);
        $this->assertArraySubset(
            [
                'Per User Monthly',
                'Premium Monthly',
                'Standard Monthly',
                'Basic Monthly',
            ], collect($stripePlans['data'])->pluck('nickname')->toArray(),
        );
    }
}