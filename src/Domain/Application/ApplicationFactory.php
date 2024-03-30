<?php

namespace App\Domain\Application;

use App\Domain\Deployment\DeploymentRepository;

class ApplicationFactory
{
    /**
     * @param string $applicationId
     * @param DeploymentRepository $deploymentRepository
     * @return Application
     */
    public static function getApplication(
        string $applicationId,
        DeploymentRepository $deploymentRepository
    ): Application {
        $application = new Application($applicationId);
        // This will become painfully slow as the number of deployments increases
        // needs to be replaced with a dedicated table instead
        $deployments = $deploymentRepository->findDeploymentWithApplication($applicationId);
        foreach ($deployments as $deployment) {
            $application->addDeployment($deployment);
        }

        return $application;
    }
}
