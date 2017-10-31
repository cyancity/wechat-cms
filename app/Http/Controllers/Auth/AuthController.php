<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
	protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }
}




