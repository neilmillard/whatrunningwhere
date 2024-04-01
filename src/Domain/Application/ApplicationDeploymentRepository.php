<?php

namespace App\Domain\Application;

use App\Infrastructure\Persistence\Application\ApplicationNotFoundException;

interface ApplicationDeploymentRepository
{
    /**
     * @param ApplicationDeployment $applicationDeployment
     * @return ?ApplicationDeployment
     */
    public function create(ApplicationDeployment $applicationDeployment): ?ApplicationDeployment;

    /**
     * @param ApplicationDeployment $applicationDeployment
     * @return ApplicationDeployment
     */
    public function updateOrCreate(ApplicationDeployment $applicationDeployment): ApplicationDeployment;

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
