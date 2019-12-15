<?php namespace Bookrr\Stripe\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use Bookrr\Stripe\Models\Settings as dbSettings;


class Settings extends Controller
{
    use \Bookrr\General\Traits\Widgets;

    public $model;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('bookrr.stripe', 'stripe.settings');
        
        $this->model = dbSettings::instance();
    }

    public function index()
    {
        $this->pageTitle = "Stripe Settings";

        $this->vars['form'] = $this->FormWidget([
            'alias'     => 'stripeForm',
            'arrayName' => 'stripe',
            'model'     => $this->model,
            'config'    => '$/bookrr/stripe/models/settings/fields.yaml'
        ]);
    }

    public function onSave()
    {
        return $this->model::set(post('stripe'));
    }
}
