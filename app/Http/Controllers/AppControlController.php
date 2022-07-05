<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppControlController extends Controller
{
    public function response(User $user)
    {
        $token = $user->createToken(str()->random(50))->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => ' required|min:4',
        ]);

        if (!Auth::attempt($cred)) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        return $this->response(Auth::user());
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'You have successfully logged out'
        ]);
    }
}
