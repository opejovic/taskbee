<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
		$this->withoutExceptionHandling();
		// Arrange
		$this->assertCount(0, User::all());

		// Act: post to register route
		$response = $this->json('POST', "/register", [
			'first_name' => 'John',
			'last_name' => 'Malkovich',
			'email' => 'john@example.com',
			'email_confirmation' => 'john@example.com',
			'password' => 'password',
			'password_confirmation' => 'password',
		]);

		// Assert: the user is created
		$this->assertCount(1, User::all());
		$user = User::whereEmail('john@example.com')->first();
		$this->assertNotNull($user);
	}
}
