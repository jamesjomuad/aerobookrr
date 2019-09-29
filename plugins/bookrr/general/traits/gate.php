<?php namespace Bookrr\General\Traits;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use Bookrr\User\Models\BaseUser as AeroUser;

trait Gate{

    public function getCustomer()
    {
        if($this->isCustomer())
            return AeroUser::auth()->user;

        return null;
    }

    public function isCustomer()
    {
        return strtolower($this->user->role->name)=='customer';
    }

    public function getCustomerId()
    {
        if($this->isCustomer())
            return $this->getCustomer()->id;

        return null;
    }

}