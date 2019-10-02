<?php namespace Bookrr\PxPay;

use Backend;
use System\Classes\PluginBase;

/**
 * PxPay Plugin Information File
 */
class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'PxPay2',
            'description' => 'PxPay implementation for Octobercms.',
            'author'      => 'jomuad',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerSettings()
    {
        return [
            'pxpay.settings' => [
                'label'       => 'Payment Gateway',
                'description' => 'PxPay2 Payment gateway.',
                'category'    => 'Bookrr',
                'icon'        => 'icon-usd',
                'url'         => Backend::url('bookrr/pxpay/settings'),
                'order'       => 900,
                'keywords'    => 'aeropark setting payment gateway',
                'permissions' => ['pxpay.*']
            ]
        ];
    }
}
