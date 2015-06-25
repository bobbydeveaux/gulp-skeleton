<?php

namespace DVO;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * DVO RPC Cleint
 *
 **/
class RpcClient
{
    protected $connection;
    protected $channel;
    protected $callback_queue;
    protected $response;
    protected $corr_id;

    public function __construct()
    {
        $this->connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $this->channel    = $this->connection->channel();
        list($this->callback_queue, ,) = $this->channel->queue_declare(
            "",
            false,
            false,
            true,
            false
        );
        $this->channel->basic_consume(
            $this->callback_queue,
            '',
            false,
            false,
            false,
            false,
            array($this, 'onResponse')
        );
    }

    public function onResponse($rep)
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }
    }

    public function call($message)
    {
        $this->response = null;
        $this->corr_id  = uniqid();

        $msg = new AMQPMessage(
            (string) $message,
            array('correlation_id' => $this->corr_id,
                  'reply_to' => $this->callback_queue)
        );
        $this->channel->basic_publish($msg, '', 'rpc_queue');

        while (!$this->response) {
            $this->channel->wait();
        }

        return $this->response;
    }
}
