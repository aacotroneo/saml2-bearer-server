<?php

namespace Aac\Oauth2\Endpoint;

use Aac\Oauth2\Http\RequestAdapter;
use Aac\Oauth2\Http\ResponseAdapter;
use Slim\Slim;

abstract class Endpoint
{
    /** @var $request RequestAdapter */
    protected $request;
    /** @var $response ResponseAdapter */
    protected $response;

    public function __construct(Slim $container = null)
    {
        $this->container = $container;
        $this->request = $this->container->request;
        $this->response = $this->container->response;
    }

    abstract public function run();

    public function jsonResponse($data, $status = 200)
    {
        $this->response->setStatusCode($status);
        $this->response->addParameters($data);
    }
}
