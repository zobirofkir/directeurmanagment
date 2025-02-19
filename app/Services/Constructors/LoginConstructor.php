<?php

namespace App\Services\Constructors;

use App\Http\Requests\LoginRequest;

interface LoginConstructor
{
    /**
     * Login constructor.
     *
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request);

    /**
     * Logout constructor.
     *
     * @param LoginRequest $request
     * @return void
     */
    public function logout(LoginRequest $request);

    /**
     * Index constructor.
     *
     * @return void
     */
    public function index();
}
