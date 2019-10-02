<?php namespace Bookrr\General\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

/**
 * General Back-end Controller
 */
class General extends Controller
{

    // public $requiredPermissions = [
    //     'aeroparks.general.*'
    // ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Aeroparks.General', 'settings');
    }

    public function index()
    {
        
    }

    public function documentation()
    {
        $this->pageTitle = 'Documentation';

        $md = \File::get(plugins_path().'/bookrr/general/controllers/general/documentation.md');
        
        return \Markdown::parse($md);
    }

    public function license()
    {
        $this->pageTitle = 'License';
    }

    public function settings()
    {
        $this->pageTitle = 'Settings';
        // return $this->makePartial('setting');
    }

}