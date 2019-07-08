<?php

class Omeka_Auth_Adapter_CAS implements Zend_Auth_Adapter_Interface
{
    private $recupInfoCASStrategie;

    /**
     * Omeka_Auth_Adapter_CAS constructor.
     * @param string file to config CAS connection
     * @param abstractRecupInfoCAS strategie to recover information
     */
    public function __construct($fileConfig, $recupInfoCASStrategie)
    {
        require_once(dirname(__FILE__) . '/../librairie/phpCAS/CAS.php');
        require_once( dirname(__FILE__) . '/../config/' . $fileConfig);
        phpCAS::client($cas_version, $cas_host, $cas_port, $cas_context);
        if ($cas_server_ca_cert_path == "") {
            phpCAS::setNoCasServerValidation();
        }
        else {
            phpCAS::setCasServerCACert($cas_server_ca_cert_path);
        }
        $this->recupInfoCASStrategie = $recupInfoCASStrategie;
    }


    /**
     *
     * @return Zend_Auth_Result $resultIdentity
     */
    public function authenticate()    {
        phpCAS::forceAuthentication();

        $this->recupInfoCASStrategie->setData(phpCAS::getAttributes());

//        var_dump($this->attr);

        $email = $this->recupInfoCASStrategie->getEmailData();
        require_once dirname(__FILE__) . '/../../../application/models/Table/User.php';
        $userTab = get_db()->getTable("User");
        $user = $userTab->findByEmail($email);
        if (empty($user)) {
            return new Zend_Auth_Result(
                Zend_Auth_Result::FAILURE,
                -1, "Non enregistré sur Omeka");
        }
        return new Zend_Auth_Result(
            Zend_Auth_Result::SUCCESS,
            $user->id,
            array("Authentication successful."));
    }
}