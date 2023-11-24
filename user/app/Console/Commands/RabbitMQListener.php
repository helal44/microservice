<?php 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class RabbitMQListener extends Command
{
    protected $signature = 'rabbitmq:listen';
    protected $description = 'Listen for RabbitMQ messages';

    public function handle()
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );

        $channel = $connection->channel();

        $channel->queue_declare(env('RABBITMQ_QUEUE'), false, true, false, false);

        $this->info('Waiting for messages. To exit press CTRL+C');

        $callback = function ($msg) {
            $this->processMessage($msg);
        };

        $channel->basic_consume(env('RABBITMQ_QUEUE'), '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }

    protected function processMessage(AMQPMessage $msg)
    {
        $data = json_decode($msg->body, true);

        // Process the message data according to your requirements
        $this->info('Received message: ' . print_r($data, true));
    }
}

