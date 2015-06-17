<?php

require_once __DIR__.'/bootstrap.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DVO\Provider\PdoServiceProvider;

$app            = new Application();

$app['guzzle.client'] = $app->share(function(){
    return new GuzzleHttp\Client(
        ['defaults' => [
            'headers'  => ['Content-Type' => 'application/json', 'Accept' => 'application/json']]]
    );
});

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Igorw\Silex\ConfigServiceProvider(
    __DIR__."/../config/" . APPLICATION_ENV . ".json"
));

// setup the hit controller
$app['controller.index'] = $app->share(function() use ($app) {
    return new DVO\Controller\IndexController($app);
});

// setup the login controller
$app['controller.login'] = $app->share(function() use ($app) {
    return new DVO\Controller\LoginController($app);
});


// Twig stuff
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));


// Use the sesion service provider
$app->register(new Silex\Provider\SessionServiceProvider());

$app->before(function (Request $request) use ($app) {

    $app['twig']->addGlobal('layout', null);
    $app['twig']->addGlobal('layout', $app['twig']->loadTemplate('layout.twig.html'));

    if (false === in_array($request->get('_route'), array_keys($app['config']['permitted_routes']))) {
        // user has a session, so ignore rest of checks
        if (null === $user = $app['session']->get('user')) {
            return $app->redirect('/login');
        }
    }

    return true;
});




$app->get('/', "controller.index:indexAction");
$app->get('/login', "controller.login:indexAction");
$app->post('/login', "controller.login:loginAction");
$app->get('/logout', "controller.login:logoutAction" );

return $app;