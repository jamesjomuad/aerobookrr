<?php namespace Bookrr\Themer\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

/**
 * Theme Back-end Controller
 */
class Theme extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Bookrr.Themer', 'bookrr.theme');
    }

    public function index()
    {
        $this->pageTitle = 'Themer';
    }

}
