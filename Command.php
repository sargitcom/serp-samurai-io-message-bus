<?php

namespace App\Lib\MessageBus;

abstract class Command
{
    abstract public static function getService(): string;
    abstract public static function getCommandName(): string;
    abstract public static function getCommandVersion(): string;
    abstract public static function fromPayload(array $payload): self;
    abstract public function getPayload(): array;

    public function __toString(): string
    {
        return json_encode([
            'service' => static::getService(),
            'eventName' => static::getCommandName(),
            'eventVersion' => static::getCommandVersion(),
            'payload' => $this->getPayload(),
        ]);
    }
}
