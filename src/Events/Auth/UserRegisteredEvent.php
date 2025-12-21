<?php

namespace SerpSamuraiIo\MessageBus\Events\Auth;

use SerpSamuraiIo\MessageBus\Event;

class UserRegisteredEvent extends Event
{
    private string $id;
    private string $email;
    private string $password;
    private string $isTos;

    public function __construct(
        string $id,
        string $email,
        string $password,
        string $isTos,
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->isTos = $isTos;
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
            'id' => $this->id,
            'email' => $this->email,
            'password' => $this->password,
            'tos' => $this->isTos,
        ];
    }

    public static function fromPayload(array $payload): self
    {
        return new self(
            $payload["id"],
            $payload["email"],
            $payload["password"],
            $payload["tos"],
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getIsTos(): string
    {
        return $this->isTos;
    }
}
