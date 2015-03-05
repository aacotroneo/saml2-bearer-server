<?php

namespace Aac\Oauth2\Http;

use OAuth2\ResponseInterface;

/**
 * A sample adapter to use the same request object for slim and bshaffer/Oauth2
 * Class ResponseAdapter.
 */
class ResponseAdapter extends \Slim\Http\Response implements ResponseInterface
{
    public function __construct($body = '', $status = 200, $headers = array())
    {
        parent::__construct($body, $status, $headers);
        $this->parameters = array();
    }

    public function finalize()
    {
        if (!empty($this->parameters)) {
            $body = $this->getBody();
            if (!empty($body)) {
                throw new \Exception("Params where added to the response, but other output was found also");
            }
            $this->body(json_encode($this->parameters));

            $this->addHttpHeaders(array("Content-Type" => 'application/json'));
        }

        return parent::finalize();
    }

    public function addParameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function getParameter($name, $default = null)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : $default;
    }

    public function addHttpHeaders(array $httpHeaders)
    {
        foreach ($httpHeaders as $key => $value) {
            $this->headers->set($key, $value);
        }
    }

    public function setStatusCode($statusCode)
    {
        $this->setStatus($statusCode);
    }

    public function setError($statusCode, $error, $errorDescription = null, $errorUri = null)
    {
        //@todo: copy paste from oauth2 php
        $parameters = array(
            'error' => $error,
            'error_description' => $errorDescription,
        );

        if (!is_null($errorUri)) {
            if (strlen($errorUri) > 0 && $errorUri[0] == '#') {
                // we are referencing an oauth bookmark (for brevity)
                $errorUri = 'http://tools.ietf.org/html/rfc6749'.$errorUri;
            }
            $parameters['error_uri'] = $errorUri;
        }

        $httpHeaders = array(
            'Cache-Control' => 'no-store',
        );

        $this->setStatusCode($statusCode);
        $this->addParameters($parameters);
        $this->addHttpHeaders($httpHeaders);

        if (!$this->isClientError() && !$this->isServerError()) {
            throw new \InvalidArgumentException(sprintf('The HTTP status code is not an error ("%s" given).', $statusCode));
        }
    }

    public function setRedirect($statusCode = 302, $url, $state = null, $error = null, $errorDescription = null, $errorUri = null)
    {
        //@todo: copy paste from oauth2 php
        if (empty($url)) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        $parameters = array();

        if (!is_null($state)) {
            $parameters['state'] = $state;
        }

        if (!is_null($error)) {
            $this->setError(400, $error, $errorDescription, $errorUri);
        }
        $this->setStatusCode($statusCode);
        $this->addParameters($parameters);

        if (count($this->parameters) > 0) {
            // add parameters to URL redirection
            $parts = parse_url($url);
            $sep = isset($parts['query']) && count($parts['query']) > 0 ? '&' : '?';
            $url .= $sep.http_build_query($this->parameters);
        }

        $this->redirect($url, $statusCode);
    }
}
