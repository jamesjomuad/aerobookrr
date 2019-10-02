<?php namespace Bookrr\Map;

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
            'author'      => 'bookrr',
            'icon'        => 'icon-map-marker'
        ];
    }

    public function registerPermissions()
    {
        return [
            'bookrr.map' => [
                'tab' => 'Bookrr',
                'label' => 'GPS Tracking'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'map' => [
                'label'       => 'Tracker',
                'url'         => Backend::url('bookrr/map/tracker'),
                'icon'        => 'icon-map-marker',
                'permissions' => ['bookrr.map'],
                'order'       => 1050,
            ],
        ];
    }
}
