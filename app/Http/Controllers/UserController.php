<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInvite;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function validateEmail(Request $request): Response
    {
        $credentials = $request->validate([
            'email' => ['required', 'email']
        ]);

        $data = [
            'user_exists' => User::whereEmail($credentials['email'])->exists(),
            'user_invite_exists' => UserInvite::whereEmail($credentials['email'])
                ->whereAccepted(false)->exists(),
        ];

        $message = '';

        if ($data['user_exists']) {
            $message = 'Please enter password';
        } elseif ($data['user_invite_exists']) {
            $message = 'Please complete registration';
        } else {
            $message = 'Cannot find your account';
        }

        return response([
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    public function completeRegistration(Request $request): Response
    {
        

        return response();
    }
}
