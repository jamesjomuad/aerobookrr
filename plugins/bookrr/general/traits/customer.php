<?php namespace Bookrr\General\Traits;

use Illuminate\Support\Arr;
use Carbon\Carbon;

trait Customer{

    public function isCustomer()
    {
        return strtolower($this->user->role->name)=='customer';
    }

}