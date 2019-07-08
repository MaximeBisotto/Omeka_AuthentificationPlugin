<?php

class Authentification_UserController extends Omeka_Controller_AbstractActionController
{
    public function loginAction()
    {
        if (current_user() != null) {
            $this->_helper->redirector->gotoUrl("/");
        }

        require_once dirname(__FILE__) . '/../form/UserLogin_form.php';
        require_once dirname(__FILE__) . '/../adapter/Omeka_Auth_Adapter_UserTable_Email.php';
        require_once dirname(__FILE__) . '/../models/Tools.php';

        $loginForm = new Omeka_Form_Login_Email;
        $this->view->form = $loginForm;

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (($loginForm instanceof Zend_Form) && !$loginForm->isValid($_POST)) {
            return;
        }

        $authAdapter = new Omeka_Auth_Adapter_UserTable_Email($this->_helper->db->getDb());
        $authAdapter->setIdentity($loginForm->getValue('email'));
        $authAdapter->setCredential($loginForm->getValue('password'));

        if (Tools::externalEmail($loginForm->getValue('email')) ===  false) {
            $this->_auth = $this->getInvokeArg('bootstrap')->getResource('Auth');
            $authResult = $this->_auth->authenticate($authAdapter);
//            var_dump($authResult);
//            exit();
            if (!$authResult->isValid()) {
                if ($log = $this->getInvokeArg('bootstrap')->logger) {
                    $ip = $this->getRequest()->getClientIp();
                    $log->info("Failed login attempt from '$ip'.");
                }
//                $this->_helper->flashMessenger($this->getLoginErrorMessages($authResult), 'error');
                return;
            }
        }
        else {
            $this->_helper->redirector->gotoUrl(Tools::externalEmail($loginForm->getValue('email')));
        }
        if ($loginForm && $loginForm->getValue('remember')) {
            // Remember that a user is logged in for the default amount of
            // time (2 weeks).
            Zend_Session::rememberMe();
        } else {
            // If a user doesn't want to be remembered, expire the cookie as
            // soon as the browser is terminated.
            Zend_Session::forgetMe();
        }

        $session = new Zend_Session_Namespace;
//        var_dump($session);
//        exit();
        if ($session->redirect) {
            $this->_helper->redirector->gotoUrl($session->redirect);
        } else {
            $this->_helper->redirector->gotoUrl('/admin');
        }
    }

    public function loginadminAction(){

        if (current_user() != null) {
            $this->_helper->redirector->gotoUrl("/");
        }

        require_once dirname(__FILE__) . '/../form/UserLogin_form.php';
        require_once dirname(__FILE__) . '/../adapter/Omeka_Auth_Adapter_UserTable_Email.php';
        require_once dirname(__FILE__) . '/../models/Tools.php';

        $loginForm = new Omeka_Form_Login_Email;
        $this->view->form = $loginForm;

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (($loginForm instanceof Zend_Form) && !$loginForm->isValid($_POST)) {
            return;
        }

        $authAdapter = new Omeka_Auth_Adapter_UserTable_Email($this->_helper->db->getDb());
        $authAdapter->setIdentity($loginForm->getValue('email'));
        $authAdapter->setCredential($loginForm->getValue('password'));

        if (Tools::externalEmail($loginForm->getValue('email')) ===  false) {
            $this->_auth = $this->getInvokeArg('bootstrap')->getResource('Auth');
            $authResult = $this->_auth->authenticate($authAdapter);
//            var_dump($authResult);
//            exit();
            if (!$authResult->isValid()) {
                if ($log = $this->getInvokeArg('bootstrap')->logger) {
                    $ip = $this->getRequest()->getClientIp();
                    $log->info("Failed login attempt from '$ip'.");
                }
//                $this->_helper->flashMessenger($this->getLoginErrorMessages($authResult), 'error');
                return;
            }
        }
        else {
            $this->_helper->redirector->gotoUrl(Tools::externalEmail($loginForm->getValue('email')));
        }
        if ($loginForm && $loginForm->getValue('remember')) {
            // Remember that a user is logged in for the default amount of
            // time (2 weeks).
            Zend_Session::rememberMe();
        } else {
            // If a user doesn't want to be remembered, expire the cookie as
            // soon as the browser is terminated.
            Zend_Session::forgetMe();
        }

        $session = new Zend_Session_Namespace;
        var_dump($session);
        exit();
        $this->_helper->redirector->gotoUrl('/');
    }

    public function casAction()
    {
        require_once dirname(__FILE__) . '/../adapter/CAS_strategie/RecupInfoCAS_Avignon.php';
        if (current_user() != null) {
            $this->_helper->redirector->gotoUrl("/");
        }

        $recupInfoCAS = new RecupInfoCAS_Avignon();
        require_once(dirname(__FILE__) . '/../adapter/Omeka_Auth_Adapter_CAS.php');
        $authAdapter = new Omeka_Auth_Adapter_CAS('avignon.php', $recupInfoCAS);
        $result = $authAdapter->authenticate();

//        echo phpCAS::getUser();
//        var_dump(phpCAS::getAttributes());
//        var_dump($result);
//        exit();

        if ($result->isValid()) {
//            echo "valide";
            $session = new Zend_Session_Namespace;
//            Zend_Session::forgetMe();
//            var_dump($session);
//            exit();
            if ($session->redirect) {
                $this->_helper->redirector->gotoUrl($session->redirect);
            } else {
                $this->_helper->redirector->gotoUrl('/admin');
            }
        }
        else {
//            echo "non valide";
//            exit();
            $this->_helper->redirector->gotoUrl('/');
        }
    }

    public function casadminAction()
    {
        require_once dirname(__FILE__) . '/../adapter/CAS_strategie/RecupInfoCAS_Avignon.php';
        if (current_user() != null) {
            $this->_helper->redirector->gotoUrl("/");
        }

        $recupInfoCAS = new RecupInfoCAS_Avignon();
        require_once(dirname(__FILE__) . '/../adapter/Omeka_Auth_Adapter_CAS.php');
        $authAdapter = new Omeka_Auth_Adapter_CAS('avignon.php', $recupInfoCAS);
        $result = $authAdapter->authenticate();

//        echo phpCAS::getUser();
//        var_dump(phpCAS::getAttributes());
//        var_dump($result);
//        exit();

        if ($result->isValid()) {
            echo "valide";
            $config = array(
                "modifiedColumn" => "modified",
                "lifetimeColumn" => "lifetime",
                "dataColumn" => "data",
            );
            Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($config));
            $session = new Zend_Session_Namespace;
//            Zend_Session::forgetMe();
            var_dump($session);
            exit();
            if ($session->redirect) {
                $this->_helper->redirector->gotoUrl($session->redirect);
            } else {
                $this->_helper->redirector->gotoUrl('/admin');
            }
        }
        else {
            echo "non valide";
            exit();
            $this->_helper->redirector->gotoUrl('/');
        }
    }

    public function changePasswordAction()
    {
        $currentUser = $this->getCurrentUser();
        if (Tools::internalEmail($currentUser->email)) {
            UsersController::changePassWordAction();
        }
        else
        {
            $this->_helper->flashMessenger(__('You can\'t change the password, you must connect you by a other authentification'), 'error');
            $this->_helper->redirector->gotoUrl('/users/login'); // TODO definir la route, peut être modifier internalMail pour dire qu'elle redirection faire.
        }
    }

    /**
     * Send an activation email to a new user telling them how to activate
     * their account.
     *
     * @param User $user
     * @return bool True if the email was successfully sent, false otherwise.
     */
    protected function sendActivationEmail($user) //TODO adapte le mail à la situation (parle d'Agorantic, pas d'activation ou de changement de mot de passe pour  externe)
    {
        if (Tools::internalEmail($user->email)) {
            $ua = $this->_helper->db->getTable('UsersActivations')->findByUser($user);
            if ($ua) {
                $ua->delete();
            }
            $ua = new UsersActivations;
            $ua->user_id = $user->id;
            $ua->save();
            // send the user an email telling them about their new user account
            $siteTitle = get_option('site_title');
            $from = get_option('administrator_email');
            $body = __('Welcome!')
                . "\n\n"
                . __('Your account for the %s repository has been created. Please click the following link to activate your account:', $siteTitle) . "\n\n"
                . WEB_ROOT . "/admin/users/activate?u={$ua->url}\n\n"
                . __('%s Administrator', $siteTitle);
            $subject = __('Activate your account with the %s repository', $siteTitle);
        }
        else
        {

        }
        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyText($body);
        $mail->setFrom($from, "$siteTitle Administrator");
        $mail->addTo($user->email, $user->name);
        $mail->setSubject($subject);
        $mail->addHeader('X-Mailer', 'PHP/' . phpversion());
        try {
            $mail->send();
            return true;
        } catch (Zend_Mail_Transport_Exception $e) {
            $logger = $this->getInvokeArg('bootstrap')->getResource('Logger');
            if ($logger) {
                $logger->log($e, Zend_Log::ERR);
            }
            return false;
        }
    }

    public function addAction()
    {
        $user = new User();

        $form = $this->_getUserForm($user);
        $this->view->form = $form;
        $this->view->user = $user;

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($_POST)) {
            $this->_helper->flashMessenger(__('There was an invalid entry on the form. Please try again.'), 'error');
            return;
        }

        $user->setPostData($_POST);
        if ($user->save(false)) {
            if ($this->sendActivationEmail($user)) {
                $this->_helper->flashMessenger(
                    __('The user "%s" was successfully added!', $user->username),
                    'success'
                );
            } else {
                $this->_helper->flashMessenger(__('The user "%s" was added, but the activation email could not be sent.',
                    $user->username));
            }
            //Redirect to the main user browse page
            $this->_helper->redirector('browse');
        } else {
            $this->_helper->flashMessenger($user->getErrors());
        }
    }
}
