<?php

namespace App\Http\Controllers\API\Auth;

use App\Enums\HttpCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Verify email attempt.
     *
     * @todo needs to be implemented
     * @param \App\Http\Requests\VerifyEmailRequest $request
     * @return JsonResponse
     */
    public function verify(Request $request, int $userId)
    {
        if (! $request->hasValidSignature())
            return response()->json([
                'message' => 'Invalid signature.'
            ], HttpCode::BAD_REQUEST);

        $user = \App\Models\User::find($userId);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->route('verification.verified');
    }

    /**
     * Requests for verification code via email.
     *
     * @return JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email is already verified.'
            ], HttpCode::BAD_REQUEST);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification link sent!'
        ], HttpCode::OK);
    }
}
