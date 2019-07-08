<?php

class AuthentificationPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array filter for the plugin
     */
    protected $_filters = array(
        'login_form',
        'admin_whitelist',
//        'public_navigation_admin_bar',
//        'public_show_admin_bar',

    );

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

        $whitelist[] = array(
            'module' => 'authentification',
            'controller' => 'user',
            'action' => 'cas'
        );
        return $whitelist;
    }

    public function filterPublicNavigationAdminBar($navLinks)
    {
        //Clobber the default admin link if user is guest
        $user = current_user();
        if ($user == null)
        $loginLabel = get_option('guest_user_login_text') ? get_option('guest_user_login_text') : __('Login');
        $registerLabel = get_option('guest_user_register_text') ? get_option('guest_user_register_text') : __('Register');
        $navLinks = array(
            'guest-user-login' => array(
                'id' => 'guest-user-login',
                'label' => $loginLabel,
                'uri' => url('/users/login')
            ),
            'guest-user-register' => array(
                'id' => 'guest-user-register',
                'label' => $registerLabel,
                'uri' => url('/users/register'),
            )
        );
        return $navLinks;
    }

    public function filterPublicShowAdminBar($show)
    {
        return true;
    }
}
