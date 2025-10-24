<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function create(LoginRequest $request)
    {
        if (Auth::guard('web')->attempt($request->only(['email', 'password']))) {
            $user = Auth::user();
            $token = $user->createToken($request->device_name ?? 'default_token')->plainTextToken;

            logger($user->can('create-task'));
            return new JsonResponse([
                'success' => true,
                'data' => [
                    'token' => $token
                ]
            ]);
        } else {
            return new JsonResponse([
                'success' => false,
                'message' => 'this user is not in our db'
            ]);
        }
    }

    public function destroy()
    {
        return 'destroy';
    }
}
