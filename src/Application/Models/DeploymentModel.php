<?php

namespace App\Application\Models;

class DeploymentModel
{
    private array $deployments = [];

    public function __construct()
    {
        $this->deployments = $_SESSION['deployments'] ?? [];
    }

    public function createDeployment($time, $application, $version, $environment)
    {
        // Create a new deployment and add it to the deployments array
        $deployment = [
            'time' => $time,
            'application' => $application,
            'version' => $version,
            'environment' => $environment
        ];
        $this->deployments[] = $deployment;
        // TODO: persist in session. probably want database at some point
        $_SESSION['deployments'] = $this->deployments;
    }

    public function getDeployments(): array
    {
        // Return the current deployed versions
        return $this->deployments;
    }
}
