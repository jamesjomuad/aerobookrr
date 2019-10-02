<?php namespace Bookrr\Rates;

use Backend;
use System\Classes\PluginBase;
use Validator;

/**
 * rates Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'rates',
            'description' => 'No description provided yet...',
            'author'      => 'bookrr',
            'icon'        => 'icon-leaf'
        ];
    }


    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Bookrr\Rates\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'bookrr.rates.some_permission' => [
                'tab' => 'rates',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'rates' => [
                'label'       => 'Rates',
                'description' => 'Manage Aeroparks Settings.',
                'category'    => 'Bookrr',
                'icon'        => 'icon-usd',
                'url'         => Backend::url('bookrr/rates/rates'),
                'order'       => 900,
                'keywords'    => 'aeropark setting',
                'permissions' => ['bookrr.rates.*']
            ]
        ];
    }
}
