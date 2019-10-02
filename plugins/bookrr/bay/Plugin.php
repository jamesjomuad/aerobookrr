<?php namespace Bookrr\Bay;

use Backend;
use System\Classes\PluginBase;

/**
 * bay Plugin Information File
 */
class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'bay',
            'description' => 'No description provided yet...',
            'author'      => 'bookrr',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerPermissions()
    {
        return [
            'bookrr.bay' => [
                'tab' => 'Bookrr',
                'label' => 'Bay & Zone permission'
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'bookrr.bay' => [
                'label'       => 'Bays / Zones',
                'description' => 'Manage parking Bays and Zones.',
                'category'    => 'Bookrr',
                'url'         => Backend::url('bookrr/bay/bay'),
                'icon'        => 'icon-car',
                'permissions' => ['bookrr.bay'],
                'order'       => 999,
            ]
        ];
    }
}
