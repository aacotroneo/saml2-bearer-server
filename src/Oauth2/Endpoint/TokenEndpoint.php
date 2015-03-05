<?php

namespace Aac\Oauth2\Endpoint;

use OAuth2\Request;
use OAuth2\Response;
use OAuth2\Server;

class TokenEndpoint extends Endpoint
{
    public function run()
    {
        $server = $this->container->oauthServer;

        try {
            // Handle a request for an OAuth2.0 Access Token and send the response to the client
            $server->handleTokenRequest($this->request, $this->response);
        } catch (\Exception $d) {
            $this->response->setStatusCode(202);
            $this->response->addParameters(['message' => $d->getMessage()]);
        }
    }
}
