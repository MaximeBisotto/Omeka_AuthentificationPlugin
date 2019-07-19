<?php

class Authentification_UserController extends Omeka_Controller_AbstractActionController
{
    /*
     * replace the users/login
     */
    public function loginAction()
    {
        if (current_user() != null) { //if is connected
            $this->_helper->redirector->gotoUrl("/");
        }

        require_once dirname(__FILE__) . '/../form/UserLogin_form.php';
        require_once dirname(__FILE__) . '/../adapter/Omeka_Auth_Adapter_UserTable_Email.php';
        require_once dirname(__FILE__) . '/../models/Tools.php';

        $loginForm = new Omeka_Form_Login_Email;
        $this->view->form = $loginForm;

        if (!$this->getRequest()->isPost()) { //if don't have data send by POST, just print the form
            return;
        }

        if (($loginForm instanceof Zend_Form) && !$loginForm->isValid($_POST)) {
            return;
        }

        if (Tools::externalEmail($loginForm->getValue('email')) ==  null) { // if user don't need to use a CAS
            $this->_auth = $this->getInvokeArg('bootstrap')->getResource('Auth');

            $authAdapter = new Omeka_Auth_Adapter_UserTable_Email($this->_helper->db->getDb());
            $authAdapter->setIdentity($loginForm->getValue('email'));
            $authAdapter->setCredential($loginForm->getValue('password'));
            $authResult = $this->_auth->authenticate($authAdapter);

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
            $this->_helper->redirector->gotoUrl(Tools::externalEmail($loginForm->getValue('email'))); //redirect to the CAS
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

        if ($session->redirect) {
            $this->_helper->redirector->gotoUrl($session->redirect);
        } else {
            $this->_helper->redirector->gotoUrl('/admin');
        }
    }

    public function loginadminAction(){ //same thinks to the function loginAction
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

        if (Tools::externalEmail($loginForm->getValue('email')) ==  null) {
            $this->_auth = $this->getInvokeArg('bootstrap')->getResource('Auth');

            $authAdapter = new Omeka_Auth_Adapter_UserTable_Email($this->_helper->db->getDb());
            $authAdapter->setIdentity($loginForm->getValue('email'));
            $authAdapter->setCredential($loginForm->getValue('password'));
            $authResult = $this->_auth->authenticate($authAdapter);

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
        if ($session->redirect) {
            $this->_helper->redirector->gotoUrl($session->redirect);
        } else {
            $this->_helper->redirector->gotoUrl('/');
        }
    }

    public function casAction() //connect to the CAS
    {
        require_once dirname(__FILE__) . '/../models/Connection.php';
        require_once dirname(__FILE__) . '/../adapter/CAS_strategie/RecupInfoCAS_Avignon.php';
        $recupInfoCAS = new RecupInfoCAS_Avignon();
        $url = Connection::cas($this, 'avignon.php', $recupInfoCAS);
        $this->_helper->redirector->gotoUrl($url);
    }

    public function casadminAction() //same thinks to the function casAction
    {
        require_once dirname(__FILE__) . '/../models/Connection.php';
        require_once dirname(__FILE__) . '/../adapter/CAS_strategie/RecupInfoCAS_Avignon.php';
        $recupInfoCAS = new RecupInfoCAS_Avignon();
        $url = Connection::cas($this, 'avignon.php', $recupInfoCAS);
        echo $url;
        $this->_helper->redirector->gotoUrl($url);
    }

    public function noaccountAction() {}

    public function noChangePasswordAction() {}

    /**
     * Send an activation email to a new user telling them how to activate
     * their account.
     *
     * @param User $user
     * @return bool True if the email was successfully sent, false otherwise.
     */
    protected function sendActivationEmail($user) //TODO adapte le mail à la situation (parle d'Agorantic, pas d'activation ou de changement de mot de passe pour  externe)
    {
        $ua = $this->_helper->db->getTable('UsersActivations')->findByUser($user);
        if ($ua) {
            $ua->delete();
        }
        $ua = new UsersActivations;
        $ua->user_id = $user->id;
        $ua->save();
        // send the user an email telling them about their new user account
        $siteTitle = get_option('site_title');
        if (Tools::externalEmail($user->email) == "/users/cas") {
            if (Tools::isStudentMail($user->email)) {
                require dirname(__FILE__) . '/../mail/avignon-all.php';
            }
            else {
                require dirname(__FILE__) . '/../mail/avignon-all.php';
            }
        }
        else {
            require dirname(__FILE__) . '/../mail/other.php';
        }

        $subject = str_replace("---siteTitle---", $siteTitle, $subject);

        $body = str_replace("---urlActivation---", WEB_ROOT . "/admin/users/activate?u=" . $ua->url, $body);
        $body = str_replace("---siteTitle---", $siteTitle, $body);

        $labelRoute = Tools::mailToLabel($user->email);
        $body = str_replace("---labelRoute---", $labelRoute, $body);


        $mail = new Zend_Mail('UTF-8');
        $mail->setBodyHtml($body);
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

    /*
     * replace the route users/add
     */
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
            $this->_helper->redirector->gotoUrl("users");
        } else {
            $this->_helper->flashMessenger($user->getErrors());
        }
    }

    protected function _getUserForm(User $user, $ua = null)
    {
        $hasActiveElement = $user->exists()
            && $this->_helper->acl->isAllowed('change-status', $user);

        $form = new Omeka_Form_User(array(
            'hasRoleElement' => $this->_helper->acl->isAllowed('change-role', $user),
            'hasActiveElement' => $hasActiveElement,
            'user' => $user,
            'usersActivations' => $ua
        ));
        $form->removeDecorator('Form');
        fire_plugin_hook('users_form', array('form' => $form, 'user' => $user));
        return $form;
    }

    public function activateAction()
    {
        $hash = $this->_getParam('u');
        $ua = $this->_helper->db->getTable('UsersActivations')->findBySql("url = ?", array($hash), true);

        if (!$ua) {
            $this->_helper->flashMessenger(__('Invalid activation code given.'), 'error');
            return $this->_helper->redirector->gotoUrl('users/forgot-password');
        }

        $user = $ua->User;
        if (Tools::externalEmail($user->email) == NULL) {
            $this->view->user = $user;
            if ($this->getRequest()->isPost()) {
                if ($_POST['new_password1'] != $_POST['new_password2']) {
                    $this->_helper->flashMessenger(__('Password: The passwords do not match.'), 'error');
                    return;
                }

                $user->setPassword($_POST['new_password1']);
            }
            else {
                return;
            }
        }
        $user->active = 1;
        if ($user->save(false)) {
            $ua->delete();
            $this->_helper->flashMessenger(__('You may now log in to Omeka.'), 'success');
            if (Tools::externalEmail($user->email) != NULL) {
                $this->_helper->redirector->gotoUrl(Tools::externalEmail($user->email));
            }
            else {
                $this->_helper->redirector->gotoUrl("users/login");
            }
        } else {
            $this->_helper->flashMessenger($user->getErrors());
        }
    }
}
