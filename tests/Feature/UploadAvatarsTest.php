<?php

namespace Tests\Feature;

use Tests\TestCase;
use taskbee\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadAvatarsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_upload_avatars()
    {
        $this->json('POST', "/profiles/1/avatar", [])->assertStatus(401); // unauthorized
    }

    /** @test */
    public function avatar_must_be_a_valid_image_file()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->json('POST', "/profiles/{$user->id}/avatar", [
                'avatar' => 'not-an-image',
            ])->assertStatus(422);
    }

    /** @test */
    public function authenticated_users_can_upload_their_avatar()
    {
        Storage::fake();

        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->json('POST', "/profiles/{$user->id}/avatar", [
                'avatar' => $file = UploadedFile::fake()->image('avatar.jpg'),
            ]);

        $this->assertEquals("http://taskbee.test/storage/avatars/{$file->hashName()}", $user->avatar_path);

        Storage::disk('public')->assertExists("avatars/{$file->hashName()}");
    }
}
