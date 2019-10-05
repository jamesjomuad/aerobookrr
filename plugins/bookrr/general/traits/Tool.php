<?php namespace Bookrr\General\Traits;

use Illuminate\Support\Arr;
use BackendAuth;
use Carbon\Carbon;

trait Tool{

    public function setDefaultNav($navs,$permission)
    {
        foreach($navs as $k=>$nav){
            foreach($navs[$k]['sideMenu'] as $key=>$val){
                if(BackendAuth::getUser()->hasPermission($permission.'.'.$key)){
                    $navs[$k]['url'] = $val['url'];
                    break;
                }
            }
        }

        return $navs;
    }

}