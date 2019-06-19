<?php

class AuthentificationPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
      'initialize',
      'define_routes',
    );

    /**
     * Add the translations.
     */
    public function hookInitialize()
    {
        // add_translation_source(dirname(__FILE__) . '/languages');
    }

    /**
     * Add the routes
     */
    public function hookDefineRoutes($args)
    {
        // Don't add these routes on the admin side to avoid conflicts.
        if (is_admin_theme()) {
            return;
        }

        $router = $args['router'];

        $router->addRoute(
            'user_login',
            new Zend_Controller_Router_Route(
                'users/login',
                array(
                    'module'       => 'authentification',
                    'controller'   => 'user',
                    'action'       => 'login',
                )
            )
        );

        $router->addRoute(
            'user_cas',
            new Zend_Controller_Router_Route(
                'users/cas',
                array(
                    'module'       => 'authentification',
                    'controller'   => 'user',
                    'action'       => 'cas',
                )
            )
        );

        $router->addRoute(
            'changePassword',
            new Zend_Controller_Router_Route(
                'users/forgotPassword',
                array(
                    'module'       => 'authentification',
                    'controller'   => 'user',
                    'action'       => 'changePassword',
                )
            )
        );
    }
}
