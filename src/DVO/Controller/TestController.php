<?php

namespace DVO\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use DVO\RpcClient;

class TestController
{
    /**
     * The RPC Client.
     */
    protected $rpc;

    /**
     * Constructor.
     *
     */
    public function __construct(RpcClient $rpc)
    {
        $this->rpc = $rpc;
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
        $response = $this->rpc->get('/user?username=arse');
        echo $response . "\n";

        $response = $this->rpc->post('/user', ['username' => 'brian']);
        echo $response . "\n";

        $response = $this->rpc->put('/user/5', ['email' => 'brian@boris.net']);
        echo $response . "\n";

        $response = $this->rpc->delete('/user/3');
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

        $body = json_encode(['username' => 'barry']);
        $response = $app['guzzle.client']->post(
            $app['config']['api_url'] . 'user',
            ['body' => $body]
        );

        print_r(json_encode($response->json()));


        $body = json_encode(['email' => 'brian@barry.com']);
        $response = $app['guzzle.client']->put(
            $app['config']['api_url'] . 'user/5',
            ['body' => $body]
        );

        print_r(json_encode($response->json()));


        $body = json_encode([]);
        $response = $app['guzzle.client']->delete(
            $app['config']['api_url'] . 'user/3',
            ['body' => $body]
        );

        print_r(json_encode($response->json()));
        die();

    }
}
