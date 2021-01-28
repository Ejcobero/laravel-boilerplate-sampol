<?php

namespace App\Http\Controllers\API\Auth;

use App\Enums\HttpCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SocialiteLoginRequest;
use App\Models\User;
use App\Rules\OauthProviderRule;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAuthController extends Controller
{
    /**
     * @param SocialiteLoginRequest $request
     * @param string $provider
     * @return mixed
     */
    public function redirectToProvider(string $provider)
    {
        if (!$this->validateProvider($provider)) return response()->json(
            [
                'error' => 'The provider you requested is not supported.'
            ],
            HttpCode::UNPROCESSABLE_ENTITY
        );

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * @param SocialiteLoginRequest $request
     * @param string $provider
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function handleProviderCallback(string $provider)
    {
        if (!$this->validateProvider($provider)) return response()->json(
            [
                'error' => 'The provider you requested is not supported.'
            ],
            HttpCode::UNPROCESSABLE_ENTITY
        );

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            info('handleProviderCallback try-catch error', [
                'exception' => $exception
            ]);

            return response()->json(['error' => 'Invalid credentials provided.'], HttpCode::UNPROCESSABLE_ENTITY);
        }

        $name = splitName($user->getName());
        $firstName = $name[0];
        $lastName = $name[1];

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail(),
            ],
            [
                'email_verified_at' => now(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => Str::random(64)
            ]
        );

        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId()
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );

        $token = $userCreated->createToken('oauth-' . $provider)->plainTextToken;

        $headers = [
            'Access-Token' => $token,
            'Token-Type' => 'Bearer'
        ];

        return response()->json($userCreated, HttpCode::OK, $headers);
    }

    /**
     * @param string $provider
     * @return bool
     */
    private function validateProvider(string $provider)
    {
        $validator = new OauthProviderRule();

        return in_array($provider, $validator::providers);
    }
}
