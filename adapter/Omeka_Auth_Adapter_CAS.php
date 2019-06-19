<?php

class Omeka_Auth_Adapter_CAS implements Zend_Auth_Adapter_Interface
{
    /**
     * Omeka_Auth_Adapter_CAS constructor.
     */
    public function __construct($fileConfig)
    {
        require_once(dirname(__FILE__) . '/../librairie/phpCAS/CAS.php');
        require_once( dirname(__FILE__) . '/../config/' . $fileConfig);
        phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
        phpCAS::setNoCasServerValidation();
//        phpCAS::setCasServerCACert($cas_server_ca_cert_path);
    }


    /**
     *
     * @param array $resultIdentity
     */
    public function authenticate()    {
        phpCAS::forceAuthentication();

        //stocke les attributs retourné par le CAS
        $this->attr = phpCAS::getAttributes();
//        var_dump($this->attr);

        // TODO verifie email

        $email = 'bisotto.maxime@gmail.com'; //pour tester le temps que je puisse recevoir l'email
        $userTab = get_db()->getTable("User");
        $user = $userTab->findByEmail($email);
        return new Zend_Auth_Result(
            Zend_Auth_Result::SUCCESS,
            //$user->id
            1
        );
    }
}