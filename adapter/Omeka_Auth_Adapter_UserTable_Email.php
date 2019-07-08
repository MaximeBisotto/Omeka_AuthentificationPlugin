<?php
/**
 * Omeka
 *
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Auth adapter that uses Omeka's users table for authentication.
 *
 * @package Omeka\Auth
 */

require_once dirname(__FILE__) . '/../../../application/libraries/Zend/Auth/Adapter/DbTable.php';
require_once dirname(__FILE__) . '/../../../application/models/User.php';

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
            'SHA1(CONCAT(salt, ?))'/* AND active = 1'*/);
    }

    /**
     * Validate the identity returned from the database.
     *
     * Overrides the Zend implementation to provide user IDs, not usernames
     * upon successful validation.
     *
     * @param array $resultIdentity
     * @todo Should this instead override _authenticateCreateAuthResult()?
     */
    protected function _authenticateValidateResult($resultIdentity)
    {
        echo "dans l'authentification";
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

class Auth_UserTable_Email {

    public function authentificate($email, $password) {
        $select = new Omeka_Db_Select();
        $select->from('omeka_users')
               ->where('email = ?', $email)
               ->where('SHA1(CONCAT(salt, ?))', $password)
               ->where('active = 1')
               ->limit(1, 0);

        echo $select;

        require_once 'Zend/Db/Table/Abstract.php';
        $zendDb = Zend_Db_Table_Abstract::getDefaultAdapter();

        if ($zendDb->getFetchMode() != Zend_DB::FETCH_ASSOC) {
            $origDbFetchMode = $this->_zendDb->getFetchMode();
            $zendDb->setFetchMode(Zend_DB::FETCH_ASSOC);
        }

        $resultIdentities = $zendDb->fetchAll($select);
        var_dump($resultIdentities);
        if (isset($origDbFetchMode)) {
            $zendDb->setFetchMode($origDbFetchMode);
            unset($origDbFetchMode);
        }

        var_dump($resultIdentities);
        if (count($resultIdentities) == 1) {
            return new Zend_Auth_Result(1, $this->$resultIdentities[0]['id']);
        }

    }

}
