<?php

namespace Hcode\Pagseguro;

use \GuzzleHttp\Client;
use \Hcode\Model\Order;

class Transporter {
    
    //Comunicação
    public static function createSession()
    {
        $client = new Client();
        $res = $client->request('POST', Config::getUrlSessions() . "?" . http_build_query(Config::getAuthentication()), [
            'verify'=>false
        ]);
    
        $xml = simplexml_load_string($res->getBody()->getContents());

        return ((string)$xml->id);
    }

    
    //Transação
	public static function sendTransaction(Payment $payment)
	{
		$client = new Client();
		
		$res = $client->request('POST', Config::getUrlTransaction() . "?" . http_build_query
		(Config::getAuthentication()), [
			"verify"=>false,
			"headers"=>[
				"Content-Type"=>"application/xml"
			],
			"body"=>$payment->getDOMDocument()->saveXml()
		]);
		
        $xml = simplexml_load_string($res->getBody()->getContents());
            
        $order = new Order();

		$order->get((int)$xml->reference);
        
        $order->setPagSeguroTransactionResponse(
			(string)$xml->code,
			(float)$xml->grossAmount,
			(float)$xml->discountAmount,
			(float)$xml->feeAmount,
			(float)$xml->netAmount,
			(float)$xml->extraAmount,
			(string)$xml->paymentLink
		);
		//var_dump($xml);
		return $xml;
	 	/* s */}
}
