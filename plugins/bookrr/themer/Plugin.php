<?php namespace Bookrr\Themer;

use Backend;
use Event;
use Request;
use System\Classes\PluginBase;


class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Aeroparks Themer',
            'description' => 'Manage dashboard theme. Elements, components etc...',
            'author'      => 'Jomuad',
            'icon'        => 'icon-leaf'
        ];
    }

    public function boot()
    {
        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params){
            // Avoid redundant resources
            if(Request::isMethod('get'))
            {
                $controller->addCss('/plugins/bookrr/themer/assets/css/fixes.css',str_random(5));
                $controller->addCss('/plugins/bookrr/themer/assets/fontawesome/css/all.css','font-awesome');
                $controller->addCss('/plugins/bookrr/themer/assets/css/mdb.min.css',str_random(5));
                $controller->addJs('/plugins/bookrr/themer/assets/js/script.js',str_random(5));
            }
        });
    }

    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'aeroparks.themer.*' => [
                'tab' => 'themer',
                'label' => 'Some permission'
            ],
        ];
    }

    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'themer' => [
                'label'       => 'themer',
                'url'         => Backend::url('bookrr/themer/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['aeroparks.themer.*'],
                'order'       => 500,
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'aeroparks.theme' => [
                'label'       => 'Themes',
                'description' => 'Manage dashboard theme. Elements, components etc...',
                'category'    => 'Aeroparks',
                'icon'        => 'icon-paint-brush',
                'url'         => Backend::url('bookrr/themer/theme'),
                'order'       => 1000,
                'keywords'    => 'aeropark setting',
                'permissions' => ['aeroparks.general.*']
            ],
        ];
    }
}
