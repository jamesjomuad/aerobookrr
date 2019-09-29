<?php namespace Bookrr\Store;

use Backend;
use System\Classes\PluginBase;
use BackendAuth;

class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Aeroparks Store',
            'description' => 'Aeroparks online Store.',
            'author'      => 'aeroparks',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerPermissions()
    {
        return [
            // Store Management
            'aeroparks.store.*' => [
                'tab' => 'Store Management',
                'label' => 'Manage Store',
                'order' => 201,
            ],

            'aeroparks.store.car-rent' => [
                'tab' => 'Store Management',
                'label' => 'View Car Rental',
                'order' => 202,
            ],

            'aeroparks.store.invoice' => [
                'tab' => 'Store Management',
                'label' => 'Manage Invoice',
                'order' => 203,
            ],

            'aeroparks.store.coupon' => [
                'tab' => 'Store Management',
                'label' => 'Manage Coupon',
                'order' => 204,
            ],

            /* Product Permision */
            'aeroparks.product.create' => [
                'tab' => 'Store Management',
                'label' => 'Can Create product',
                'order' => 205,
            ],
            'aeroparks.product.read' => [
                'tab' => 'Store Management',
                'label' => 'Can View product',
                'order' => 206,
            ],
            'aeroparks.product.update' => [
                'tab' => 'Store Management',
                'label' => 'Can Update product',
                'order' => 207,
            ],
            'aeroparks.product.delete' => [
                'tab' => 'Store Management',
                'label' => 'Can Remove product',
                'order' => 208,
            ],
            /* end Permision */

            /* Product Category Permision */
            'aeroparks.productCategory.create' => [
                'tab' => 'Store Management',
                'label' => 'Can Create product category',
                'order' => 205,
            ],
            'aeroparks.productCategory.read' => [
                'tab' => 'Store Management',
                'label' => 'Can View product category',
                'order' => 206,
            ],
            'aeroparks.productCategory.update' => [
                'tab' => 'Store Management',
                'label' => 'Can Update product category',
                'order' => 207,
            ],
            'aeroparks.productCategory.delete' => [
                'tab' => 'Store Management',
                'label' => 'Can Remove product category',
                'order' => 208,
            ],
            /* end Permision */
            
            /* Rule Permision */
            'aeroparks.rule.create' => [
                'tab' => 'Store Management',
                'label' => 'Can Create rule',
                'order' => 209,
            ],
            'aeroparks.rule.read' => [
                'tab' => 'Store Management',
                'label' => 'Can View rule',
                'order' => 210,
            ],
            'aeroparks.rule.update' => [
                'tab' => 'Store Management',
                'label' => 'Can Update rule',
                'order' => 211,
            ],
            'aeroparks.rule.delete' => [
                'tab' => 'Store Management',
                'label' => 'Can Remove rule',
                'order' => 212,
            ],
            /* end Permision */
        ];
    }

    public function registerNavigation()
    {
        if(BackendAuth::getUser()->isCustomer())
        {
            return [
                'car-rental' => [
                    'label'       => 'Car Rental',
                    'url'         => Backend::url('aeroparks/store/carrent'),
                    'icon'        => 'fa fa-file-invoice',
                    'permissions' => ['aeroparks.store.car-rent'],
                    'order'       => 910,
                ]
            ];
        }

        return [
            'store' => [
                'label'       => 'Store',
                'url'         => Backend::url('aeroparks/store/carrent'),
                'icon'        => 'fa fa-store-alt',
                'permissions' => ['aeroparks.store.*'],
                'order'       => 910,

                'sideMenu' => [
                    'car-rental' => [
                        'label'       => 'Car Rental',
                        'url'         => Backend::url('aeroparks/store/carrent'),
                        'icon'        => 'fa fa-file-invoice',
                        'permissions' => ['aeroparks.store.car-rent'],
                    ],
                    'product' => [
                        'label'       => 'Product / Service',
                        'url'         => Backend::url('aeroparks/store/product'),
                        'icon'        => 'icon-shopping-basket',
                        'permissions' => ['aeroparks.product.read'],
                    ],
                    'cart' => [
                        'label'       => 'Carts',
                        'url'         => Backend::url('aeroparks/store/cart'),
                        'icon'        => 'icon-cart-plus',
                        'permissions' => ['aeroparks.product.read'],
                    ],
                    'invoice' => [
                        'label'       => 'Invoice',
                        'url'         => Backend::url('aeroparks/store/invoice'),
                        'icon'        => 'fa fa-file-invoice',
                        'permissions' => ['aeroparks.store.invoice'],
                    ],
                    'coupon' => [
                        'label'       => 'Coupon',
                        'url'         => Backend::url('aeroparks/store/coupon'),
                        'icon'        => 'icon-certificate',
                        'permissions' => ['aeroparks.store.coupon'],
                    ],
                    'category' => [
                        'label'       => 'Category',
                        'url'         => Backend::url('aeroparks/store/category'),
                        'icon'        => 'icon-bookmark-o',
                        'permissions' => ['aeroparks.productCategory.*'],
                    ],
                    'rules' => [
                        'label'       => 'Rule Set',
                        'url'         => Backend::url('aeroparks/store/rule'),
                        'icon'        => 'icon-list',
                        'permissions' => ['aeroparks.rule.*'],
                    ]
                ]
            ]
        ];
    }

}
