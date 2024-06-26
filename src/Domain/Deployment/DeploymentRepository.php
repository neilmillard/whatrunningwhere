<?php

namespace App\Domain\Deployment;

interface DeploymentRepository
{
    /**
     * @param int $timeStampFrom
     * @param int $timeStampTo
     * @return Deployment[]
     */
    public function findAll(int $timeStampFrom, int $timeStampTo): array;

    /**
     * @param int $id
     * @return Deployment|null
     * @throws DeploymentNotFoundException
     */
    public function findDeploymentOfId(int $id): ?Deployment;

    /**
     * @param string $application
     * @return Deployment[]
     */
    public function findDeploymentWithApplication(string $application): array;

    /**
     * @param Deployment $deployment
     * @return Deployment
     */
    public function create(Deployment $deployment): Deployment;

    /**
     * @return string[]
     */
    public function findApplications(): array;
}
