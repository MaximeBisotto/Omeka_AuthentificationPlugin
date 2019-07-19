<?php


class Connection
{
    /*
     * simplifie the CAS connection in the controller
     *
     * @return the URL to redirect the user
     */
    public static function cas($context, $config, $recupInfo) {
        require_once dirname(__FILE__) . '/../adapter/CAS_strategie/RecupInfoCAS_Avignon.php';
        if (current_user() != null) { // if are connected
            return "/";
        }

        require_once(dirname(__FILE__) . '/../adapter/Omeka_Auth_Adapter_CAS.php');
        $authAdapter = new Omeka_Auth_Adapter_CAS($config, $recupInfo);
        $context->_auth = $context->getInvokeArg('bootstrap')->getResource('Auth');
        $result = $context->_auth->authenticate($authAdapter); // authentificate the user

        if (!$result->isValid()) {
            if ($result->getCode(-1)) { // error after CAS connection, the user don't have account in Omeka database
                return "/users/no_account";
            }
            return "/";
        }
        else {
            // If a user doesn't want to be remembered, expire the cookie as
            // soon as the browser is terminated.
            Zend_Session::forgetMe();
            $session = new Zend_Session_Namespace; // create the session
            if ($session->redirect) {
                return $session->redirect;
            } else {
                return "/admin";
            }
        }
    }
}