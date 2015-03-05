<?php

namespace Aac\Oauth2\Endpoint;

use GuzzleHttp\Client;

require_once __DIR__.'/../vendor/autoload.php';
/* show all errors! */
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($argc  != 2) {
    echo "\nUsage: php test [base url server]\n\n";
} else {
    $cli = new test();
    $cli->run($argv[1]);
}

class test
{
    public function run($baseUrl)
    {
        $assertion = $this->getValidSamlAssertionFromSomeWhere();
        $grant_type = 'urn:ietf:params:oauth:grant-type:saml2-bearer';
        $client_id = 'testclient';
        $client_secret = 'testpass';
        $client = new Client();

        try {
            $tokenUrl = $baseUrl.'/token';
            $response = $client->post($tokenUrl, [
                    'body' => compact("assertion", "grant_type", "client_id", "client_secret"),
                    'exceptions' => false,
                ]
            );
            $tokenResponse = $response->json();

            $tokenInfoUrl = "$baseUrl/tokeninfo?access_token={$tokenResponse['access_token']}";

            $response = $client->get($tokenInfoUrl, [
                    'exceptions' => false,
                ]
            );
            $tokenInfoResponse = $response->json();

            echo "\nReponse Url:\n$baseUrl/token\n";
            print_r($tokenResponse);
            echo "\nTokeninfo Url:\n $tokenInfoUrl\n";
            print_r($tokenInfoResponse);
            echo "\n";
        } catch (\GuzzleHttp\Exception\ParseException $e) {
            print_r($response->getStatusCode());
            print_r($response->getBody());
        }
    }

    private function getValidSamlAssertionFromSomeWhere()
    {
        //note we are using strict mode = false, so It only validates sintax and signature
        return file_get_contents(__DIR__.'/../inst/sample_saml_assertion.xml.base64');
    }
}
