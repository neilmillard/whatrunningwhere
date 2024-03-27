<?php

namespace App\Infrastructure\Persistence\Deployment;

use App\Domain\Deployment\Deployment;
use App\Domain\Deployment\DeploymentRepository;
use PDO;

class SqLiteDeploymentRepository implements DeploymentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function findAll(): array
    {
        $query = $this->connection->prepare("SELECT * FROM deployments");
        $query->execute();
        return $query->fetchall();
    }

    public function findDeploymentOfId(int $id): Deployment
    {
        $query = $this->connection->prepare("SELECT * FROM deployments WHERE id=?", [$id]);
        $query->execute();
        $result = $query->fetchObject('App\Domain\Deployment\Deployment');
        return $result[0];
    }

    public function findDeploymentWithApplication(string $application): array
    {
        $query = $this->connection->prepare("SELECT * FROM deployments WHERE application=?", $application);
        $query->execute();
        return $query->fetchAll();
    }

    public function createDeployment(
        string $application,
        string $version,
        string $who,
        string $time,
        string $environment
    ): Deployment {
        $sql = "INSERT INTO deployments (application, version, who, time, environment) VALUES (?,?,?,?,?)";
        $query = $this->connection->prepare($sql);
        $query->execute([$application, $version, $who, $time, $environment]);
        $id = $this->connection->lastInsertId();
        return $this->findDeploymentOfId($id);
    }

    public function save(Deployment $deployment): Deployment
    {
        $sql = "INSERT INTO deployments (application, version, who, time, environment) VALUES (?,?,?,?,?)";
        $query = $this->connection->prepare($sql);
        $query->execute([
            $deployment->getApplication(),
            $deployment->getVersion(),
            $deployment->getWho(),
            $deployment->getTime(),
            $deployment->getEnvironment()]);
        $id = $this->connection->lastInsertId();
        return $this->findDeploymentOfId($id);
    }
}
