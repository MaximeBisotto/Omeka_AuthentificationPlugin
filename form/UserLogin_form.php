<?php


class Omeka_Form_Login_Email extends Omeka_Form
{
    public function init()
    {
        parent::init();

        $this->setMethod('post');
        $this->setAttrib('id', 'login-form');
        $this->addElement('text', 'email', array(
            'label' => __('Email'),
            'required' => true,
            'validators' => array(
                array('validator' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => __('email cannot be empty.'))))),
            'decorators' => array(
                'ViewHelper',
                array(array('input' => 'HtmlTag'), array('tag' => 'div', 'class' => 'inputs six columns omega')),
                array('Label', array('tag' => 'div', 'tagClass' => 'two columns alpha')),
                array('HtmlTag', array('tag' => 'div', 'class' => 'field')))));
        $this->addElement('password', 'password', array(
            'label' => __('Password'),
            'required' => true,
            'validators' => array(
                array('validator' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => __('Password cannot be empty.'))))),
            'decorators' => array(
                'ViewHelper',
                array(array('input' => 'HtmlTag'), array('tag' => 'div', 'class' => 'inputs six columns omega')),
                array('Label', array('tag' => 'div', 'tagClass' => 'two columns alpha')),
                array('HtmlTag', array('tag' => 'div', 'class' => 'field')))));
        $this->addElement('checkbox', 'remember', array(
            'class' => 'checkbox',
            'label' => __('Remember Me?'),
            'decorators' => array(
                'ViewHelper',
                array(array('input' => 'HtmlTag'), array('tag' => 'div', 'class' => 'inputs six columns omega')),
                array('Label', array('tag' => 'div', 'tagClass' => 'two columns alpha')),
                array('HtmlTag', array('tag' => 'div', 'class' => 'field')))));
        $this->addDisplayGroup(array('email', 'password', 'remember'), 'login');
        $this->addElement('submit', 'submit', array('label' => __('Log In')));

//        $tag = new Zend_Form_Element_Html('login_cas');
//        $tag->setValue('<a href="/users/cas">Connecte toi avec l\'Université d\'Avignon</a>');
//        $this->addElement($tag);

        $this->addElement(new Zend_Form_Element_Note(array(
            'name' => 'login_cas',
            'value' => __('Se connecter avec l\'Université d\'Avignon'),
            'decorators' => array(
                array('ViewHelper'),
                array('HtmlTag', array(
                    'tag' => 'a',
                    'href' => $this->getView()->url('/users/cas')                   ))
                ),)

            )
        );

    }
}
