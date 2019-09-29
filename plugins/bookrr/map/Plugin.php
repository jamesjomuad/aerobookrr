<?php namespace Aeroparks\Map;

use Backend;
use System\Classes\PluginBase;

/**
 * map Plugin Information File
 */
class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Map',
            'description' => 'Visualize your customer.',
            'author'      => 'aeroparks',
            'icon'        => 'icon-map-marker'
        ];
    }

    public function registerPermissions()
    {
        return [
            'aeroparks.map' => [
                'tab' => 'Aeroparks',
                'label' => 'GPS Tracking'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'map' => [
                'label'       => 'Tracker',
                'url'         => Backend::url('aeroparks/map/tracker'),
                'icon'        => 'icon-map-marker',
                'permissions' => ['aeroparks.map'],
                'order'       => 1050,
            ],
        ];
    }
}
