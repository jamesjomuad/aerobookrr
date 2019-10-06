<?php namespace Bookrr\Rates;

use Backend;
use System\Classes\PluginBase;
use Validator;

/**
 * rates Plugin Information File
 */
class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'rates',
            'description' => 'No description provided yet...',
            'author'      => 'bookrr',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerPermissions()
    {
        return [
            'bookrr.rates' => [
                'tab' => 'Bookrr',
                'label' => 'Manage Rates'
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'rates' => [
                'label'       => 'Rates',
                'description' => 'Manage Aeroparks Settings.',
                'category'    => 'Bookrr Park',
                'icon'        => 'icon-usd',
                'url'         => Backend::url('bookrr/rates/rates'),
                'order'       => 900,
                'keywords'    => 'aeropark setting',
                'permissions' => ['bookrr.rates']
            ]
        ];
    }
}
