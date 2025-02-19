<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\Facades\LoginFacade;

class LoginController extends Controller
{
    /**
     * Index constructor.
     *
     */
    public function index()
    {
        return LoginFacade::index();
    }

    /**
     * Login constructor.
     *
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        return LoginFacade::login($request);
    }

    /**
     * Logout constructor.
     *
     * @param LoginRequest $request
     */
    public function logout(LoginRequest $request)
    {
        return LoginFacade::logout($request);
    }
}
