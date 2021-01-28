<?php

namespace Tests\Feature\Auth;

use App\Enums\Roles;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\BaseTestCase;

class AuthProfileImageTest extends BaseTestCase
{
    public function nahtestUploadBase64ProfileImage()
    {
        Storage::fake('profile_images');

        $this->actingAsFakeUser(Roles::RegularUser);

        // $fakeBase64Image = base64_encode($this->faker->image);
        $image = UploadedFile::fake()->image('avatar.jpg');
        $fakeBase64Image = base64_encode($image);

        $response = $this->post('/api/auth/profile-images/base64', [
            'base_64_data' => $fakeBase64Image
        ], self::headers);

        dd([
            'response' => $response->getContent(),
            'fakeBase64Image' => $fakeBase64Image,
            'fakeImage' => $this->faker->image
        ]);

        $response->assertOk();
    }
}
