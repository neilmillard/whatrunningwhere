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
        $deployments = [];
        $query = $this->connection->prepare("SELECT * FROM deployments");
        $query->execute();
        $data = $query->fetchall(PDO::FETCH_ASSOC);
        foreach ($data as $n) {
            $deployments[] = $this->getDeploymentResult($n);
        }
        return $deployments;
    }

    /**
     * @param int $id
     * @return Deployment|null
     */
    public function findDeploymentOfId(int $id): ?Deployment
    {
        $query = $this->connection->prepare("SELECT * FROM deployments WHERE id=?", [$id]);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return null;
        }
        return $this->getDeploymentResult($result);
    }

    /**
     * @param array $result
     * @return Deployment
     */
    public function getDeploymentResult(mixed $result): Deployment
    {

        return new Deployment(
            $result['time'],
            $result['who'],
            $result['application'],
            $result['version'],
            $result['environment'],
            $result['id']
        );
    }

    public function findDeploymentWithApplication(string $application): array
    {
        $query = $this->connection->prepare("SELECT * FROM deployments WHERE application=?", $application);
        $query->execute();
        return $query->fetchAll();
    }

    public function create(Deployment $deployment): Deployment
    {
        $data = [
            'application' => $deployment->getApplication(),
            'version' => $deployment->getVersion(),
            'who' => $deployment->getWho(),
            'time' => $deployment->getTime(),
            'environment' => $deployment->getEnvironment()
        ];
        $sql = "INSERT INTO deployments (application, version, who, time, environment) "
            . "VALUES (:application, :version, :who, :time, :environment)";
        $query = $this->connection->prepare($sql);
        $query->execute($data);
        if ($query->rowCount() > 0) {
            $deployment->setId($this->connection->lastInsertId());
        }
        return $deployment;
    }
}
