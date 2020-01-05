<?php 

use \Hcode\Page;
use \Hcode\Model\User;
use \Hcode\PagSeguro\Config;
use \Hcode\Pagseguro\Transporter;
use \Hcode\Pagseguro\Document;
use \Hcode\Pagseguro\Phone;
use \Hcode\Pagseguro\Address;
use \Hcode\Pagseguro\Sender;
use \Hcode\Pagseguro\Shipping;
use \Hcode\Pagseguro\Item;
use \Hcode\Pagseguro\Payment;
use \Hcode\PagSeguro\CreditCard;
use \Hcode\Pagseguro\CreditCard\Installment;
use \Hcode\Pagseguro\CreditCard\Holder;
use \Hcode\Model\Order;

//payment credito
$app->post('/payment/credit', function(){
	
	User::verifyLogin(false);
	
	$order = new Order();
	
	$order->getFromSession();

	//Testado
	$order->get((int)$order->getidorder());

	$address = $order->getAddress();
	
	$cart = $order->getCart();

	//CPF Testado
	$cpf = new Document(Document::CPF, $_POST['cpf']);

	//Telefone Testado
	$phone = new Phone($_POST['ddd'], $_POST['phone']);

	//Dados Testado
	$shippingAddress = new Address(
        $address->getdesaddress(),
        $address->getdesnumber(),
        $address->getdescomplement(),       
        $address->getdesdistrict(),
        $address->getdeszipcode(),
        $address->getdescity(),
        $address->getdesstate(),
        $address->getdescountry()
	);
	
	//Data Testado
	$birthDate = new DateTime($_POST['birth']);

	//Dados Testado
	$sender = new Sender($order->getdesperson(), $cpf, $birthDate, $phone, $order->getdesemail(), $_POST['hash']);
	
	//Dados Testado
	$holder = new Holder($order->getdesperson(), $cpf, $birthDate, $phone);

	//Entrega Testado
	$shipping = new Shipping($shippingAddress, (float)$cart->getvlfreight(), Shipping::PAC);

	//Parcelas testado
	$installment = new Installment((int)$_POST["installments_qtd"], (float)$_POST["installments_value"]);

	//Dados Testado
	$billingAddress = new Address(
        $address->getdesaddress(),
        $address->getdesnumber(),
        $address->getdescomplement(),       
        $address->getdesdistrict(),
        $address->getdeszipcode(),
        $address->getdescity(),
        $address->getdesstate(),
        $address->getdescountry()
    );
	
	//Cartão testado
	$creditCard = new CreditCard($_POST['token'], $installment, $holder, $billingAddress);

	//Pagamento - Item 
	$payment = new Payment($order->getidorder(), $sender, $shipping);

	//Item
	foreach($cart->getProducts() as $product)
	
	{
		
        $item = new Item(
            (int)$product['idproduct'],
            $product['desproduct'],
            (float)$product['vlprice'],
            (int)$product['nrqtd']
        );
		
		$payment->addItem($item);
	};

	$payment->setCreditCard($creditCard);

	Transporter::sendTransaction($payment);

	echo json_encode([
        'success'=>true
    ]);

});

//payment
$app->get('/payment', function(){
	
	User::verifyLogin(false);

	$order = new Order();

	$order->getFromSession();

	$years = [];

	for ($y = date('Y'); $y < date('Y')+14; $y++)
	{
		array_push($years, $y);
	}

	$page = new Page();

	$page->setTpl("payment", [
		"order"=>$order->getValues(),
		"msgError"=>Order::getError(),
		"years"=>$years,
		"pagseguro"=>[
				"urlJS"=>Config::getUrlJS(),
				"id"=>Transporter::createSession(),
				"maxInstallmentNoInterest"=>Config::MAX_INSTALLMENT_NO_INTEREST,
            	"maxInstallment"=>Config::MAX_INSTALLMENT
		]
	]);
});