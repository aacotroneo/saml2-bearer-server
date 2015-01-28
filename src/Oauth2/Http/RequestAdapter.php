<?php
namespace Aac\Oauth2\Http;

use OAuth2\RequestInterface;

/**
 * A sample adapter to use the same request for slim and bshaffer/Oauth2
 * Class RequestAdapter
 */
class RequestAdapter extends \Slim\Http\Request implements  RequestInterface{

    public function query($name, $default = null)
    {
        return $this->get($name, $default);
    }

    public function request($name, $default = null)
    {
        return $this->post($name, $default);
    }

    public function server($name, $default = null)
    {
        return $this->env[$name]?:$default;
    }

    public function getAllQueryParameters()
    {
        return $this->get();
    }
}