<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
//        $this->validateEmail($request);
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    public function broker()
    {
        return Password::broker();
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'Email sent',
            'response' => $response
        ], 200);
//        return response()->json(['message' => 'Email sent'], trans($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'Failed to send mail',
            'response' => $response
            ], 500);
    }
}
