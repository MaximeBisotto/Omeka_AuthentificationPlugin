<?php

class AuthentificationPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array filter for the plugin
     */
    protected $_filters = array(
        'login_form',
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

            require_once dirname(__FILE__) . '/config/route.php';

            foreach ($route as $item) {
                $name = 'admin_' . $item['name'];
                $action = $item['action'] . 'admin';
                $router->addRoute(
                    $name,
                    new Zend_Controller_Router_Route(
                        $item['route'],
                        array(
                            'module'       => 'authentification',
                            'controller'   => 'user',
                            'action'       => $action,
                        )
                    )
                );
            }


            $router->addRoute(
                'admin_adduser',
                new Zend_Controller_Router_Route(
                    'users/add',
                    array(
                        'module'       => 'authentification',
                        'controller'   => 'user',
                        'action'       => 'add',
                    )
                )
            );

            $router->addRoute(
                'admin_user_activate',
                new Zend_Controller_Router_Route(
                    'users/activate',
                    array(
                        'module'       => 'authentification',
                        'controller'   => 'user',
                        'action'       => 'activate',
                    )
                )
            );

//            $router->addRoute(
//                'admin_users_forgot-password',
//                new Zend_Controller_Router_Route(
//                    'users/forgot-password',
//                    array(
//                        'module'       => 'authentification',
//                        'controller'   => 'user',
//                        'action'       => 'forgotPasswordadmin',
//                    )
//                )
//            );
            return;
        }

        require_once dirname(__FILE__) . '/config/route.php';

        foreach ($route as $item) {
            $router->addRoute(
                $item['name'],
                new Zend_Controller_Router_Route(
                    $item['route'],
                    array(
                        'module'       => 'authentification',
                        'controller'   => 'user',
                        'action'       => $item['action'],
                    )
                )
            );
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
            'user_noaccount',
            new Zend_Controller_Router_Route(
                'users/noaccount',
                array(
                    'module'       => 'authentification',
                    'controller'   => 'user',
                    'action'       => 'noaccount',
                )
            )
        );

//        $router->addRoute(
//            'user_forgot-password',
//            new Zend_Controller_Router_Route(
//                'users/forgot-password',
//                array(
//                    'module'       => 'authentification',
//                    'controller'   => 'user',
//                    'action'       => 'forgotPassword',
//                )
//            )
//        );
    }

    public function filterLoginForm($form) {
        echo get_view()->partial('user/redirectLogin.php');
    }

    /**
     * Filter the admin interface whitelist.
     *
     * Allows our custom login action to be accessed without logging in.
     */
    public function filterAdminWhitelist($whitelist)
    {
        $whitelist[] = array(
            'module' => 'authentification',
            'controller' => 'user',
            'action' => 'loginadmin'
        );

        include dirname(__FILE__) . '/config/route.php';

        foreach ($route as $item) {
            $action = $item['action'] . 'admin';
            $whitelist[] = array(
                'module' => 'authentification',
                'controller' => 'user',
                'action' => $action
            );
        }
        $whitelist[] = array(
            'module' => 'authentification',
            'controller' => 'user',
            'action' => 'activate'
        );
//
//        $whitelist[] = array(
//            'module' => 'authentification',
//            'controller' => 'user',
//            'action' => 'forgotPasswordadmin'
//        );
        return $whitelist;
    }
}
