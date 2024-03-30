<?php

namespace App\Domain\Application;

use App\Domain\Deployment\Deployment;
use JsonSerializable;

class Application implements JsonSerializable
{
    private string $application;

    private array $deployments;

    public function __construct(string $application)
    {
        $this->application = $application;
    }

    public function addDeployment(Deployment $deployment): bool
    {
        if ($deployment->getApplication() == $this->application) {
            $this->deployments[$deployment->getEnvironment()] = $deployment;
        } else {
            return false;
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->application,
            'deployed' => $this->deployments,
        ];
    }
}
