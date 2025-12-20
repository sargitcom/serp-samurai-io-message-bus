<?php

namespace Kamil\MessageBus\Events\Auth;

use Kamil\MessageBus\Event;

class UserRegisteredEvent extends Event
{
    private string $test;

    public function __construct(string $test)
    {
        $this->test = $test;
    }

    public static function getService(): string
    {
        return 'auth';
    }

    public static function getEventName(): string
    {
        return 'user-registered';
    }

    public static function getEventVersion(): string
    {
        return '1.0';
    }

    public function getPayload(): array
    {
        return [
            'test' => $this->test,
            'test2' => 'test2',
        ];
    }

    public static function fromPayload(array $payload): self
    {
        return new self($payload["test"]);
    }
}
