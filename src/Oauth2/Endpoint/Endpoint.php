<?php

namespace Aac\Oauth2\Endpoint;

use Aac\Oauth2\Http\RequestAdapter;
use Aac\Oauth2\Http\ResponseAdapter;
use Slim\Slim;


abstract class Endpoint
{

    /**@var $container Slim */
    protected $container;
    /** @var $request RequestAdapter */
    protected $request;
    /** @var $response ResponseAdapter */
    protected $response;


    function __construct(Slim $container = null)
    {
        $this->container = $container;
        $this->request = $this->container->request;
        $this->response = $this->container->response;
    }

    abstract function run();


    function jsonResponse($data, $status = 200)
    {
        $this->response->setStatusCode($status);
        $this->response->addParameters($data);
    }


} 