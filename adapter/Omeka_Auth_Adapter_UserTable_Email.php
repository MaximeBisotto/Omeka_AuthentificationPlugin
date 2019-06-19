<?php

/**
 * Auth adapter that uses Omeka's users table for authentication.
 */

class Omeka_Auth_Adapter_UserTable_Email extends Zend_Auth_Adapter_DbTable
{
    /**
     * @param Omeka_Db $db Database object.
     */
    public function __construct(Omeka_Db $db)
    {
        parent::__construct($db->getAdapter(),
            $db->User,
            'email',
            'password',
            'SHA1(CONCAT(salt, ?)) AND active = 1');
    }

    /**
     * Validate the identity returned from the database with the email and the password.
     *
     * @param array $resultIdentity
     */
    protected function _authenticateValidateResult($resultIdentity)
    {
        $authResult = parent::_authenticateValidateResult($resultIdentity);
        if (!$authResult->isValid()) {
            return $authResult;
        }
        // This auth result uses the username as the identity, what we need
        // instead is the user ID.
        $correctResult = new Zend_Auth_Result($authResult->getCode(), $this->_resultRow['id'], $authResult->getMessages());
        return $correctResult;
    }
}
