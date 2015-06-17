<?php

namespace DVO\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController
{

    protected $app;
    /**
     * Constructor.
     *
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Handles the HTTP GET.
     *
     * @param Request     $request The request.
     * @param Application $app     The app.
     *
     */
    public function indexAction(Request $request, Application $app)
    {
        $data = [];
        $data['active'] = 'login';
        $data['errors'] = [];

        $options = [
            'cost' => 11,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        ];

        // username and password is username / password
        // echo password_hash("password", PASSWORD_BCRYPT, $options)."\n";


        return $app['twig']->render('login.twig.html', $data);
    }

    public function loginAction(Request $request, Application $app)
    {
        $email    = $request->request->get('email');
        $password = $request->request->get('password');

        // Communicate over rest
        $user = [];

        $app['session']->set('user', $user);

        return $app->redirect('/');

    }

    public function logoutAction(Request $request, Application $app)
    {
        $app['session']->clear();

        return $app->redirect('/');
    }

}
