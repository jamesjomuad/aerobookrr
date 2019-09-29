<?php namespace Aeroparks\User\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Loyalty Back-end Controller
 */
class Loyalty extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Aeroparks.User', 'user', 'loyalty');
        // BackendMenu::setContext('Aeroparks.User', 'loyalty');
    }


    public function index()
    {
        $this->pageTitle = 'Reward Points';
        $this->vars['points'] = $this->user->points ? $this->user->points->points : 0 ;
    }

    public function test()
    {
        dd(
            $this->user->points->points
            // ->create(['points'=>5])
        );
    }

}
