<?php

namespace App\Domain\Deployment;

use JsonSerializable;

class Deployment implements JsonSerializable
{
    private ?int $id;

    private string $who;

    private string $application;

    private string $version;

    private string $environment;

    private int $time;

    public function __construct(
        int $time,
        string $who,
        string $application,
        string $version,
        string $environment,
        ?int $id
    ) {
        $this->id = $id;
        $this->who = strtolower($who);
        $this->application = strtolower($application);
        $this->version = $version;
        $this->environment = strtolower($environment);
        $this->time = $time;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWho(): string
    {
        return $this->who;
    }

    public function getApplication(): string
    {
        return $this->application;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'who' => $this->who,
            'application' => $this->application,
            'version' => $this->version,
            'environment' => $this->environment,
            'time' => $this->time,
        ];
    }
}