<?php

namespace App\Infrastructure\Persistence\Deployment;

use App\Domain\Deployment\Deployment;
use App\Domain\Deployment\DeploymentRepository;
use PDO;

class SqLiteDeploymentRepository implements DeploymentRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function findAll(): array
    {
        // TODO: Implement findAll() method.
        return [];
    }

    public function findDeploymentOfId(int $id): Deployment
    {
        // TODO: Implement findDeploymentOfId() method.

        //return $user;
    }

    public function findDeploymentwithApplication(string $application): array
    {
        // TODO: Implement findDeploymentwithApplication() method.
        return [];
    }
}