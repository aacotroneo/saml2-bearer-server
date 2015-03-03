<?php
namespace Aac\Oauth2\Endpoint;

use OAuth2\Server;
use Slim\Slim;

class AuthorizeEndpoint extends Endpoint
{
    /** @var $server Server */
    protected $server;

    public function __construct(Slim $container)
    {
        parent::__construct($container);

        $this->server = $container->oauthServer;
    }

    public function run()
    {
        //@TODO.
        //Just follow original tutorials. Make calls like the following and don't call  $response->send();
        //the response is finalized by slim and correctly handled (note response::send is not part of the interface)
        // $this->server->validateAuthorizeRequest($this->request, $this->response);
        // $this->server->handleAuthorizeRequest($this->request, $this->response, $authorized, $userid);

        //I wired twig in this setup so you may use
        $this->container->render('authorize.twig');
    }
}
