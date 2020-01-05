<?php

namespace Hcode\PagSeguro;

class Config {
    
    //sanbox = teste / production = em execução
    //sandbox = true -> teste
    //sandbos = false -> executando
    const SANDBOX = true;

    //Email
    const SANDBOX_EMAIL = "yanzoka@ymail.com";
    const PRODUCTION_EMAIL = "yanzoka@ymail.com";
    
    //Token (senha)
    const SANDBOX_TOKEN = "A3216DE8A65D43B08576AE4C85C37B25";
    const PRODUCTION_TOKEN = "ad244912-c827-432a-bd4d-65e7fa27204e8eac7231422cb09e82f5e5f156fd6c5c55bc-ed17-450f-8741-fb22402eb23c";

    //Iniciar sessão
    const SANDBOX_SESSIONS = "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions";
    const PRODUCTION_SESSIONS = "https://ws.pagseguro.uol.com.br/v2/sessions";

    //Iniciar o javascript do pagseguro
    const SANDBOX_URL_JS = "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js";
    const PRODUCTION_URL_JS = "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js";

    //Transação
    const SANDBOX_URL_TRANSACTION = "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions";
    const PRODUCTION_URL_TRANSACTION = "https://ws.pagseguro.uol.com.br/v2/transactions";

    //Endereço de pós compra
    const NOTIFICATION_URL = "http://www.html5dev.com.br/payment/notification";

    //Parcelas
    //Sem juros
    const MAX_INSTALLMENT_NO_INTEREST = 8;
    //Com juros
    const MAX_INSTALLMENT = 12;

    //Função de autenticação
    public static function getAuthentication():array
    {
        if (Config::SANDBOX === true)
        {
            return [
                "email"=>Config::SANDBOX_EMAIL,
                "token"=>Config::SANDBOX_TOKEN
            ];
        } else {
            return [
                "email"=>Config::PRODUCTION_EMAIL,
                "token"=>Config::PRODUCTION_TOKEN
            ];
        }
    }

    //Função da Sessão
    public static function getUrlSessions():string
    {
        return (Config::SANDBOX === true) ? Config::SANDBOX_SESSIONS : Config::PRODUCTION_SESSIONS;
    }

    //Função da Sessão no Javascript
    public static function getUrlJS()
    {
        return (Config::SANDBOX === true) ? Config::SANDBOX_URL_JS : Config::PRODUCTION_URL_JS;
    }

    //Transação
    public static function getUrlTransaction()
	{
		return (Config::SANDBOX === true) ? Config::SANDBOX_URL_TRANSACTION :
		Config::PRODUCTION_URL_TRANSACTION;
	}
}