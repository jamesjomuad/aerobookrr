<?php namespace Bookrr\General\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

/**
 * Setting Back-end Controller
 */
class Setting extends Controller
{
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'setting');
        SettingsManager::setContext('Aeroparks.General', 'aeroparks.setting');
    }

    public function index()
    {
        $this->pageTitle = "Settings";
    }

    public function rate()
    {
        $this->pageTitle = "Rate Settings";
        SettingsManager::setContext('Aeroparks.General', 'aeroparks.rate');
    }
}
