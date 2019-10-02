<?php

#******************************************************************************
#* Name          : PxPay_OpenSSL.inc.php
#* Description   : Payment Express PxPay PHP OpenSSL Sample
#* Copyright	 : Payment Express 2017(c)
#* Date			 : 2017-04-10
#* References	 : https://www.paymentexpress.com/developer-e-commerce-paymentexpress-hosted-pxpay
#*@version	     : 2.0
#* Author        : Payment Express DevSupport
#******************************************************************************



class PxPay_OpenSSL
{
	var $PxPay_Key;
	var $PxPay_Url;
	var $PxPay_Userid;
	function __construct($Url, $UserId, $Key){
		error_reporting(E_ERROR);
		$this->PxPay_Key = $Key;
		$this->PxPay_Url = $Url;
		$this->PxPay_Userid = $UserId;
	}
	
	#******************************************************************************
	# Create a request for the PxPay interface
	#******************************************************************************
	function makeRequest($request)
	{
		#Validate the Request
		if($request->validData() == false) return "" ;

		$request->setUserId($this->PxPay_Userid);
		$request->setKey($this->PxPay_Key);
		
		$xml = $request->toXml();
		
		$result = $this->submitXml($xml, true);
  
		return $result;
		
	}
			
	#******************************************************************************
	# Return the transaction outcome details
	#******************************************************************************
	function getResponse($result){
				
		$inputXml = "<ProcessResponse><PxPayUserId>".$this->PxPay_Userid."</PxPayUserId><PxPayKey>".$this->PxPay_Key.
		"</PxPayKey><Response>".$result."</Response></ProcessResponse>";
		
		$outputXml = $this->submitXml($inputXml, false);
		
		$pxresp = new PxPayResponse($outputXml);
		return $pxresp;	
	}
	
	#******************************************************************************
	# Actual submission of XML using OpenSSL. Returns output XML
	#******************************************************************************
	  function submitXml($inputXml, $isGenerateRequset){
  
  		// parsing the given URL
       $URL_Info=parse_url($this->PxPay_Url);

       // Building referrer
       $referer=$_SERVER["SCRIPT_URI"];

       // Find out which port is needed - if not given use standard (=80)
       if(!isset($URL_Info["port"]))
         $URL_Info["port"]=443;

       // building POST-request:
       $requestdata.="POST ".$URL_Info["path"]." HTTP/1.1\n";
       $requestdata.="Host: ".$URL_Info["host"]."\n";
       $requestdata.="Referer: $referer\n";
       $requestdata.="Content-type: application/x-www-form-urlencoded\n";
       $requestdata.="Content-length: ".strlen($inputXml)."\n";
       $requestdata.="Connection: close\n";
       $requestdata.="\n";
       $requestdata.=$inputXml."\n";
	  $fp = fsockopen("ssl://".$URL_Info["host"],$URL_Info["port"]);

       fputs($fp, $requestdata);
       while(!feof($fp)) {
           $result .= fgets($fp, 128);
       }
       fclose($fp);
	     
		 # Find where start of XML is
		  if($isGenerateRequset)
			  $xmlStart = "<Request";
		  else
			  $xmlStart = "<Response";
			  
		$outputXml = strstr($result, $xmlStart);
  
		  return $outputXml;
	  }
	
}