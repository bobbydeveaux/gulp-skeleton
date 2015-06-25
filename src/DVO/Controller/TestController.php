<?php

namespace DVO\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use DVO\RpcClient;

class TestController
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
    public function rpcAction(Request $request, Application $app)
    {
        $rpc = new RpcClient();
        $message = json_encode(['method' => 'GET', 'path' => '/user?username=arse']);
        $response = $rpc->call($message);
        echo $response . "\n";
        die();
    }

    /**
     * Handles the HTTP GET.
     *
     * @param Request     $request The request.
     * @param Application $app     The app.
     *
     */
    public function restAction(Request $request, Application $app)
    {
        $response = $app['guzzle.client']->get(
            $app['config']['api_url'] . 'user',
            ['query' => ['username' => 'arse']]
        );

        print_r(json_encode($response->json()));
        die();

    }
}
