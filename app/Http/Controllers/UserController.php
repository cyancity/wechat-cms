<?php

namespace App\Controllers;

use Auth;
use Hash;
use Image;
use Validator;
use Illuminate\Http\Request;
use App\Notification\FollowedUser;
use App\Repositories\UserRepository;

class UserController extends Controller
{
	protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function index()
    {
    	if (Auth::check()) {
    		return redirect()->to('/user/'.Auth::user()->name);
    	}

    	return redirect()->to('/login');
    }

    public function show($username)
    {
    	$user = $this->user->getByName($username);

        if (!isset($user)) abort(404);

    	$discussions = $user->discussions;
    	$comments = $user->comments;

    	return view('user.index', compact('user', 'discussions', 'comments'));
    }

    /**
     * Display the user following list
     */
    public function following($username)
    {
		$user = $this->user->getByName($username);

        if (!isset($user)) abort(404);

		$followings = $user->followings;

		return view('user.following', compact('user', 'followings'));
    }

    public function discussion($username)
    {
    	$user = $this->user->getByName($username);

        if (!isset($user)) abort(404);

    	$discussions = $user->discussions;

    	return view('user.discussion', compact('user', 'discussions'));
    }

    public function comments($username)
    {
        $user = $this->user->getByName($username);

        if (!isset($user)) abort(404);

        $comments = $user->comments;

        return view('user.comments', compact('user', 'comments'));
    }

    public function doFollow($id)
    {
    	$user = $this->user->getById($id);

    	if (Auth::user()->isFollowing($id)) {
    		Auth::user()->unFollow($id);
    	} else {
    		Auth::user()->follow($id);

    		$user->notify(new FollowedUser(Auth::user()));
    	}

    	return redirect()->back();
    }

    public function edit()
    {
    	if (!Auth::id()) {
    		abort(404);
    	}

    	$user = $this->user->getById(Auth::id());

    	return view('user.profile', compact('user'));
    }

    public function update(Request $request, $id)
    {
    	$input = $request->except(['name', 'email', 'is_admin']);

    	$user = $this->user->getById($id);

    	$this->authorize('update', $user);

    	$this->user->update($id, $input);

    	return redirect()->back();
    }

    public function changePassword(Request $request)
    {
    	if (! Hash::check($request->get('password'), Auth::user()->password)) {
    		return redirect()->back()
                             ->withErrors(['old_password' => 'The password must be the same of current password.']);
    	}

    	Validator::make($request->all(), [
    		'old_password' => 'required|max:255',
    		'password' => 'required|confirmed|min:6'
    	])->validate();

    	$this->user->changePassword(Auth::user(), $request->get('password'));

    	return redirect()->back();
    }

    public function notifications()
    {
    	if (!Auth::id()) {
    		abort(404);
    	}

    	$user = $this->user->getById(Auth::id());

    	return view('user.notifications', compact('user'));
    }

    public function markAsRead()
    {
    	if (!Auth::id()) {
    		abort(404);
    	}

    	$user = $this->user->getById(Auth::id());

    	$user->unreadNotification->markAsRead();

    	return view('user.notifications', compact('user'));
    }
}


