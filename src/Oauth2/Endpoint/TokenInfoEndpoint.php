<?php
namespace Aac\Oauth2\Endpoint;

use OAuth2\Server;

class TokenInfoEndpoint extends Endpoint
{
    public function run()
    {
        /** @var $server Server */
        $server = $this->container->oauthServer;

        $token = $server->getAccessTokenData($this->request);

        if (!empty($token)) {
            $this->jsonResponse($token);
        } else {
            $this->jsonResponse(array('error' => 'Token is invalid or expired'));
        }
    }
}
