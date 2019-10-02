<?php namespace Bookrr\Pxpay\Models;

use Model;

/**
 * settings Model
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'pxpay';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
