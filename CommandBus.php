<?php

namespace App\Lib\MessageBus;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use RuntimeException;

class CommandBus
{
    public function sendCommand(Command $command): void
    {
        $connection = new AMQPStreamConnection('ms-starter-rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->exchange_declare('commands', 'direct', false, true, false);
        $msg = new AMQPMessage((string)$command);
        $eventHandle = $command::getService() . "-" .  $command::getCommandName() . "-" . $command::getCommandVersion();
        $channel->basic_publish($msg, 'commands', $eventHandle);
        $channel->close();
        $connection->close();
    }

    public function listenCommand(callable $callback, string $className): void
    {
        if (!is_callable($callback)) {throw new RuntimeException("Is not callable");}

        $connection = new AMQPStreamConnection('ms-starter-rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->exchange_declare('commands', 'direct', false, true, false);

        list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

        $eventHandle = $className::getService() . "-" .  $className::getEventName() . "-" . $className::getEventVersion();

        $channel->queue_bind($queue_name, 'commands', $eventHandle);

        echo " [*] Waiting for events. To exit press CTRL+C\n";

        $innerCallback = function (AMQPMessage $msg) use ($callback, $className) {
            $data = json_decode($msg->getBody(), true);

            $class = implode('', array_map(function (string $element) {
                return ucfirst($element);
            }, explode("-", $data['eventName'])));

            $eventClass = "App\\Lib\\MessageBus\\Events\\" . ucfirst($data['service']) . "\\" . $class . "Event";

            if ($eventClass !== $className) {
                return;
            }

            $callback($className::fromPayload($data['payload']));
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $innerCallback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

        $channel->close();
        $connection->close();
    }
}
