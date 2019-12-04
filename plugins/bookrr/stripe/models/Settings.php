<?php namespace Bookrr\Stripe\Models;

use Model;


/**
 * Settings Model
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'stripe';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public static function getSettings()
    {
        $settings = json_decode(self::instance()->toJSON());
        $setting = (object)[];

        # Default
        if(!$settings)
        {
            return (object)[
                'currency' => 'USD',
                'symbol' => '$'
            ];
        }

        if($settings AND $settings->mode==0){
            $setting->pubKey = $settings->sandbox_pubkey;
            $setting->key    = $settings->sandbox_key;
        }else if($settings){
            $setting->pubKey = $settings->pubkey;
            $setting->key    = $settings->key;
        }

        $currency = explode('_',$settings->currency);

        $setting->currency = $currency[0] ? : 'NZD';

        $setting->symbol = $currency[1] ? : '$';

        $setting->receipt_email = $settings->receipt_email;
        
        return $setting;
    }

}
