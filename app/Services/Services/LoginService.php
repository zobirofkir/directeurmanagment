<?php

namespace App\Services\Services;

use App\Http\Requests\LoginRequest;
use App\Services\Constructors\LoginConstructor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Session;

class LoginService implements LoginConstructor
{
    /**
     * Index constructor.
     *
     * @return void
     */
    public function index()
    {
        return inertia('Home');
    }

    /**
     * Login constructor.
     *
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']])) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'error' => 'Invalid credentials',
        ], 401);
    }

    /**
     * Logout constructor.
     *
     * @param LoginRequest $request
     */
    public function logout(LoginRequest $request)
    {
        $user = Auth::user();

        if ($user) {
            Token::where('user_id', $user->id)->delete();
            RefreshToken::where('user_id', $user->id)->delete();
        }

        Session::forget('authToken');

        return inertia('Home');
    }
}
