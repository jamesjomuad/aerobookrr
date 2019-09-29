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
            'author'      => 'aeroparks',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerPermissions()
    {
        return [
            'aeroparks.bay' => [
                'tab' => 'Aeroparks',
                'label' => 'Bay & Zone permission'
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'aeroparks.bay' => [
                'label'       => 'Bays / Zones',
                'description' => 'Manage parking Bays and Zones.',
                'category'    => 'Aeroparks',
                'url'         => Backend::url('aeroparks/bay/bay'),
                'icon'        => 'icon-car',
                'permissions' => ['aeroparks.bay'],
                'order'       => 999,
            ]
        ];
    }
}
