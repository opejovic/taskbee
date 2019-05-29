<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function authenticated_users_cannot_see_the_registration_page()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('register')->assertRedirect('home');
        
    }

    /** @test */
    function guests_can_see_the_registration_page()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('register')->assertViewIs('auth.register');
    }

    /** @test */
    function guests_can_register()
    {
        // Arrange
        $this->assertCount(0, User::all());

        // Act: post to register route
        $response = $this->json('POST', "/register", [
            'first_name' => 'John',
            'last_name' => 'Malkovich',
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        // Assert: the user is created
        $this->assertCount(1, User::all());
        $user = User::whereEmail('john@example.com')->first();
        $this->assertNotNull($user);
    }
}