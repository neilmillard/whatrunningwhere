<?php

namespace App\Domain\Application;

use App\Infrastructure\Persistence\Application\ApplicationNotFoundException;

interface ApplicationDeploymentRepository
{
    /**
     * @param string $name
     * @param string $environment
     * @throws ApplicationNotFoundException
     * @return ApplicationDeployment
     */
    public function findApplicationDeployment(string $name, string $environment): ApplicationDeployment;

    /**
     * @param string $applicationId
     * @return ApplicationDeployment[]
     */
    public function findApplicationDeployments(string $applicationId): array;
}
