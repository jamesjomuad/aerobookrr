<?php namespace Aeroparks\Notify;

use Backend;
use Event;
use Request;
use System\Classes\PluginBase;

/**
 * notify Plugin Information File
 */
class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Notification',
            'description' => 'No description provided yet...',
            'author'      => 'aeroparks',
            'icon'        => 'icon-leaf'
        ];
    }

    public function boot()
    {
        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            // Avoid redundant resources
            if(Request::isMethod('get'))
            {
                $controller->addCss('/plugins/aeroparks/notify/assets/notify.css',str_random(5));
                $controller->addJs('/plugins/aeroparks/notify/assets/notify.js',str_random(5));
            }
        });
    }

    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'aeroparks.notify.some_permission' => [
                'tab' => 'notify',
                'label' => 'Some permission'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'notify' => [
                'label'       => '0',
                'url'         => Backend::url('aeroparks/notify/notify'),
                'icon'        => 'icon-bell',
                // 'permissions' => ['aeroparks.notify.*'],
                'order'       => 5
            ],
        ];
    }
}
