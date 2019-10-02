<?php namespace PxPay;

use Bookrr\Pxpay\Models\Settings;

class PxPay{
    
	public $PxPay_Url = "https://sec.paymentexpress.com/pxaccess/pxpay.aspx";
    public $PxPay_Userid;
    public $PxPay_Key;
    public $Currency = "NZD";
    public $UrlFail;
    public $UrlSuccess;
    public $_onRequest;
    public $Url;
    public $RefNum;
    private $Pxpay;
    private $Request;
    private $Response;
    private $Settings;
    
    function __construct(){
        $this->Settings     = self::getSettings();
        $this->PxPay_Userid = $this->Settings->username;
        $this->PxPay_Key    = $this->Settings->key;
        $this->PxPay_Url    = $this->Settings->gatewayUrl ? : $this->PxPay_Url ;
        $this->Currency     = $this->Settings->currency;
        $this->RefNum       = crc32(uniqid()).time() ;
        $this->UrlSuccess   = $this->Settings->successUrl;
        $this->UrlFail      = $this->Settings->failUrl;
    }
    
    public function request($data = [])
    {
        $this->Pxpay    = new \PxPay_Curl( $this->PxPay_Url, $this->PxPay_Userid, $this->PxPay_Key );
        $this->Request  = new \PxPayRequest();
        $TxnId          = uniqid("ID");    #Generate a unique identifier for the transaction
        $this->RefNum   = isset($data['reference']) ? $data['reference'] : $this->RefNum ;

        #Set PxPay properties
        $this->Request->setAmountInput($data['amount']);
        $this->Request->setTxnData1($data['address1']);
        $this->Request->setTxnData2($data['address2']);
        $this->Request->setTxnData3($data['address3']);
        $this->Request->setEmailAddress($data['email']);
        $this->Request->setMerchantReference($this->RefNum);
        $this->Request->setTxnType("Purchase");
        $this->Request->setCurrencyInput($this->Currency);
        $this->Request->setTxnId($TxnId);

        $this->Request->setUrlFail($this->UrlFail);			# can be a dedicated failure page
        $this->Request->setUrlSuccess($this->UrlSuccess);   # can be a dedicated success page
        
        #The following properties are not used in this case
        # $this->Request->setEnableAddBillCard($EnableAddBillCard);    
        # $this->Request->setBillingId($BillingId);
        # $this->Request->setOpt($Opt);
        
        #Call makeRequest function to obtain input XML
        $request_string = $this->Pxpay->makeRequest($this->Request);
        
        #Obtain output XML
        $response = new \MifMessage($request_string);
        
        #Parse output XML
        $this->Url = $response->get_element_text("URI");
        $valid = $response->get_attribute("valid");

        if($valid AND $this->Url AND $this->_onRequest)
        {
            $callback = $this->_onRequest;
            $callback($response);
        }

        return $this;
    }

    public function send()
    {
        #Redirect to payment page
        redirect()->to($this->Url)->send();
    }

    public static function getSettings()
    {
        $settings = json_decode(Settings::instance()->toJSON());
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
            $setting->username = $settings->sandbox_username;
            $setting->key = $settings->sandbox_key;
        }else if($settings){
            $setting->username = $settings->username;
            $setting->key = $settings->key;
        }

        $currency = explode('_',$settings->currency);

        $setting->currency = $currency[0] ? : 'NZD';

        $setting->symbol = $currency[1] ? : '$';

        $setting->gatewayUrl = $settings->url_gateway;

        $setting->successUrl = $settings->url_success ? : $settings->url_success ;

        $setting->failUrl = $settings->url_fail ? : $settings->url_fail ;
        
        return $setting;
    }

    public function onRequest($fn)
    {
        $this->_onRequest = $fn;
        return $this;
    }

}