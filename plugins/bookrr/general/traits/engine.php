<?php namespace Aeroparks\General\Traits;

use Illuminate\Support\Arr;
use Carbon\Carbon;

trait Engine{

    public function system()
    {
        // dd($this);
        return '<p class="flash-message static warning">Demo Mode <a href="/backend/aeroparks/general/license">activate</a></p>';
    }


}