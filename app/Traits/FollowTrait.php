<?php

namespace App\Traits;

trait FollowTrait
{
    public function follow($user)
    {
        return $this->followings()->sync((array)$user, false);
    }

    public function unfollow($user)
    {
    	return $this->followings()->detach((array)$user);
    }

    public function isFollowing($user)
    {
    	return $this->followings->contains($user);
    }

    public function isFollowedBy($user)
    {
    	return $this->followings->contains($user);
    }

    public function followers()
    {
    	return $this->belongsToMany(__CLASS__, 'followers', 'follow_id', 'user_id')->withTimestamps();
    }

    public function followings()
    {
    	return $this->belongsToMany(__CLASS__, 'followers', 'user_id', 'follow_id')->withTimestamps();
    }
}