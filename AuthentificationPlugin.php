<?php

class AuthentificationPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array filter for the plugin
     */
    protected $_filters = array(
//        'login_form',
        'admin_whitelist',
//        'login_adapter',
//        'public_navigation_admin_bar',
//        'public_show_admin_bar',

    );

    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
//        'initialize',
        'define_routes',
    );

    /**
     * Add the translations.
     */
//    public function hookInitialize()
//    {
//        // add_translation_source(dirname(__FILE__) . '/languages');
//    }

    /**
     * Add the routes
     */
    public function hookDefineRoutes($args)
    {
        $router = $args['router'];

        if (is_admin_theme()) {
//            $router->addRoute(
//                'changePassword',
//                new Zend_Controller_Router_Route(
//                    'users/forgotPassword',
//                    array(
//                        'module'       => 'authentification',
//                        'controller'   => 'user',
//                        'action'       => 'changePassword',
//                    )
//                )
//            );
            $router->addRoute(
                'admin_user_login',
                new Zend_Controller_Router_Route(
                    'users/login',
                    array(
                        'module'       => 'authentification',
                        'controller'   => 'user',
                        'action'       => 'loginadmin',
                    )
                )
            );

            $router->addRoute(
                'admin_user_cas',
                new Zend_Controller_Router_Route(
                    'users/cas',
                    array(
                        'module'       => 'authentification',
                        'controller'   => 'user',
                        'action'       => 'casadmin',
                    )
                )
            );
            return;
        }

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

//        $router->addRoute(
//            'changePassword',
//            new Zend_Controller_Router_Route(
//                'users/forgotPassword',
//                array(
//                    'module'       => 'authentification',
//                    'controller'   => 'user',
//                    'action'       => 'changePassword',
//                )
//            )
//        );
    }

//    public function filterLoginForm($form) {
//        echo get_view()->partial('user/redirectLogin.php');
//    }

    /**
     * Filter the admin interface whitelist.
     *
     * Allows our custom login action to be accessed without logging in.
     */
    public function filterAdminWhitelist($whitelist)
    {
//        array(
//            array('module' => 'authentification', 'controller' => 'users', 'action' => 'loginadmin'),
//            array('module' => 'authentification', 'controller' => 'users', 'action' => 'casadmin'),
//////            array('controller' => 'users', 'action' => 'forgot-password'),
//////            array('controller' => 'installer', 'action' => 'notify'),
//////            array('controller' => 'error', 'action' => 'error')
//        );
        $whitelist[] = array(
            'module' => 'authentification',
            'controller' => 'user',
            'action' => 'loginadmin'
        );

        $whitelist[] = array(
            'module' => 'authentification',
            'controller' => 'user',
            'action' => 'casadmin'
        );
        return $whitelist;
    }
}
