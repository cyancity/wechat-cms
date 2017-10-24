<?php

namespace App\Console\Commands;

use App\User;
use Validator;
use RuntimeExpection;
use Identicon\Identicon;
use App\Scopes\StatusScope;
use Illuminate\Console\Command;

/**
* 
*/
class CreateAdmin extends Command
{
	protected $signature = 'blog:admin
							{user? : The ID of the user}
							{--delete : Whether the user should be deleted}';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		$userId = $this->argument('user');
		$option = $this->option('delete');

		if ($userId && !$option) {
			$user = User::findOrFail($userId);
			$this->info('username:'.$user->name.', email: '.$user->email.', is_admin:'.$user->is_admin);
			return;
		} elseif ($userId && $option) {
			if (User::withoutGlobalScope(StatusScope::class)->find($userId)->delete()) {
				$this->info('Successfully delete the user');
			} else {
				$this->error('Error occured, need to fix');
			}
			return;
		}

		$name = $this->ask('What`s your name');
		$email = $this->ask('What`s your email');
		$password = $this->secret('What`s the password(min: 6 char)');

		$data = [
			'name' => $name,
			'email' => $email,
			'password' => $password 
		];

		if ($this->register($data)) {
			$this->info('Successfully registered an admin, you can login then');
		} else {
			$this->error('Error Occured');
		}
	}

	public function register($data)
	{
		$validator = Validator::make($data, [
			'name' => 'required|max:255|unique:users',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:6'
		]);

		if (!$validator->passes()) {
			throw new RuntimeExpection($validator->errors()->first());
		}

		return $this->create($data);
	}

	public function create($data)
	{
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'status' => true,
			'is_admin' => true,
			'password' => bcrypt($data['password']),
			'avatar' => (new Identicon())->getImageDataUri($data['name'], 256)
		]);
	}
}





