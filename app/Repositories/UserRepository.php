<?php

namespace App\Repositories;

use Auth;
use App\User;
use App\Scopes\StatusScope;

/**
 * summary
 */
class UserRepository
{
	use IndexRepository;

	protected $model;
	
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getList()
    {
    	return $this->model
    	    ->orderBy('id','desc')
    	    ->get();
    }

    public function getByName($name)
    {
    	return $this->model
    				->where('name', $name)
    				->first();
    }

    public function page($number = 10, $sort = 'desc', $sortColumn = 'created_at')
    {
    	return $this->model->withoutGlobalScope(StatusScope::class)->orderBy($sortColumn, $sort)->paginate($number);
    }

    public function getById($id)
    {
    	return $this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
    }

    public function update($id, $input)
    {
    	$this->model = $this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);

    	return $this->save($this->model, $input);
    }

    public function changePassword($user, $password)
    {
    	return $user->update(['password' => bcrypt($password)]);
    }

    public function saveAvatar($id, $avatar)
    {
    	$user = $this->getById($id);

    	$user->avatar = $avatar;

    	return $user->save();
    }

    // public function destory($id)
    // {
    // 	return $this->getById($id)->delete();
    // }
}
