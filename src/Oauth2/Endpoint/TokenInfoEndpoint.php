<?php
namespace Aac\Oauth2\Endpoint;


use Slim\Slim;

class TokenInfoEndpoint extends Endpoint
{

    function run()
    {

        $server = $this->container->oauthServer;

        $token = $server->getAccessTokenData($this->request);

        if (!empty($token)) {
            $this->jsonResponse($token);
        } else {
            $this->jsonResponse(array('error' => 'Token is invalid or expired'));
        }

    }


}
