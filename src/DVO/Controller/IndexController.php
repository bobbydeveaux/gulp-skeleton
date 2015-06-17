<?php

namespace DVO\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{

    /**
     * The App.
     */
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
        $data['active'] = 'home';

        return $app['twig']->render('home.twig.html', $data);
    }


}
