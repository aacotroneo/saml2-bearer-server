<?php

namespace Aac\Oauth2\Endpoint;

class CreateAssertionEndpoint extends Endpoint
{
    public function run()
    {
        $samlFile = $this->container->settings['saml']['settings_file'];
        $settings = include_once($samlFile);
        $forceAuthn = false;
        $isPassive  = false;
        $returnTo   = null;
        $parameters = array();

        $auth = new \OneLogin_Saml2_Auth($settings);

        $authnRequest = new \OneLogin_Saml2_AuthnRequest($auth->getSettings(), $forceAuthn, $isPassive);

        $parameters['SAMLRequest'] = $authnRequest->getRequest();
        $parameters['RelayState']  = $returnTo ?: \OneLogin_Saml2_Utils::getSelfRoutedURLNoQuery();

        $security = $auth->getSettings()->getSecurityData();
        if (isset($security['authnRequestsSigned']) && $security['authnRequestsSigned']) {
            $signature = $auth->buildRequestSignature($parameters['SAMLRequest'], $parameters['RelayState']);
            $parameters['SigAlg'] = \XMLSecurityKey::RSA_SHA1;
            $parameters['Signature'] = $signature;
        }

        $this->jsonResponse(array(
            'url' => $auth->getSSOurl(),
            'parameters' => $parameters,
        ));
    }
}
