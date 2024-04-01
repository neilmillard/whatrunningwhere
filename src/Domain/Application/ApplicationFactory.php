<?php

namespace App\Domain\Application;

use App\Domain\Deployment\DeploymentRepository;

class ApplicationFactory
{
    /**
     * @param string $applicationId
     * @param ApplicationDeploymentRepository $applicationDeploymentRepository
     * @param DeploymentRepository $deploymentRepository
     * @return Application
     */
    public static function getApplication(
        string $applicationId,
        ApplicationDeploymentRepository $applicationDeploymentRepository,
        DeploymentRepository $deploymentRepository
    ): Application {
        $application = new Application($applicationId);
        $applicationDeployments = $applicationDeploymentRepository
            ->findApplicationDeployments($applicationId);

        foreach ($applicationDeployments as $appDeployment) {
            $deployment = $deploymentRepository->findDeploymentOfId($appDeployment->getDeployment());
            $application->addDeployment($deployment);
        }

        return $application;
    }
}
