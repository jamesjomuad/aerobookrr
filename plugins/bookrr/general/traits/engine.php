<?php namespace Bookrr\General\Traits;

use Illuminate\Support\Arr;
use Carbon\Carbon;

trait Engine{

    public function system()
    {
        // dd($this);
        return '<p class="flash-message static warning">Demo Mode <a href="/backend/bookrr/general/license">activate</a></p>';
    }


}