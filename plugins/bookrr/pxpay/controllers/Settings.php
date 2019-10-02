<?php namespace Bookrr\Pxpay\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use Jomuad\Pxpay\Models\Settings as dbSettings;


class Settings extends Controller
{

    use \Aeroparks\General\Traits\Widgets;

    public $model;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Jomuad.pxpay', 'pxpay.settings');
        
        $this->model = dbSettings::instance();
    }

    public function index()
    {
        $this->pageTitle = "PxPay Settings";

        $this->vars['form'] = $this->newFormWidget([
            'alias'     => 'PxPayForm',
            'arrayName' => 'PxPay',
            'model'     => $this->model,
            'config'    => '$/jomuad/pxpay/models/settings/fields.yaml'
        ]);
    }

    public function onSave()
    {
        return $this->model::set(post('PxPay'));
    }

}
