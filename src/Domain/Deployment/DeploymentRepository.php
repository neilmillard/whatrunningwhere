<?php

namespace App\Domain\Deployment;

interface DeploymentRepository
{
    /**
     * @return Deployment[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Deployment
     * @throws DeploymentNotFoundException
     */
    public function findDeploymentOfId(int $id): Deployment;

    /**
     * @param string $application
     * @return Deployment[]
     */
    public function findDeploymentwithApplication(string $application): array;
}
