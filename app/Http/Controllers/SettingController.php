<?php

namespace App\Http\Controllers;

use Illiminate\Http\Request;
use App\Repositories\UserRepository;

class SettingController extends Controller
{
	protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function index()
    {
    	return view('setting.index');
    }

    public function notification()
    {
    	return view('setting.notification');
    }

    public function update(Request $request)
    {
    	$input = [
    		'email_notify_enabled' => $request->get('email_notify_enabled' ? 'yes' : 'no')
    	];

    	$this->user->update(\Auth::id(), $input);

    	return redirect()->back();
    }

    public function binding()
    {
    	return view('setting.binding');
    }
}