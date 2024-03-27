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
     * @param string $version
     * @param string $who
     * @param string $time
     * @param string $environment
     * @return Deployment
     */
    public function createDeployment(
        string $application,
        string $version,
        string $who,
        string $time,
        string $environment
    ): Deployment;

    /**
     * @param string $application
     * @return Deployment[]
     */
    public function findDeploymentwithApplication(string $application): array;

    /**
     * @param Deployment $deployment
     * @return Deployment
     */
    public function save(Deployment $deployment): Deployment;
}
