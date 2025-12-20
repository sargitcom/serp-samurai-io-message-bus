<?php

namespace App\Lib\MessageBus;

abstract class Event
{
    abstract public static function getService(): string;
    abstract public static function getEventName(): string;
    abstract public static function getEventVersion(): string;
    abstract public static function fromPayload(array $payload): self;
    abstract public function getPayload(): array;

    public function __toString(): string
    {
        return json_encode([
            'service' => static::getService(),
            'eventName' => static::getEventName(),
            'eventVersion' => static::getEventVersion(),
            'payload' => $this->getPayload(),
        ]);
    }
}
