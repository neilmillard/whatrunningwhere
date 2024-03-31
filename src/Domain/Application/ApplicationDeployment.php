<?php

namespace App\Domain\Application;

class ApplicationDeployment
{
    private string $name;
    private string $environment;
    private int $deployment_id;

    public function __construct(string $name, string $environment, int $deployment_id)
    {
        $this->name = strtolower($name);
        $this->environment = strtolower($environment);
        $this->deployment_id = $deployment_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getDeployment(): int
    {
        return $this->deployment_id;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'environment' => $this->environment,
            'deployment_id' => $this->deployment_id,
        ];
    }
}
