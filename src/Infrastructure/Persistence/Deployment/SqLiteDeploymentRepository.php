<?php

namespace App\Infrastructure\Persistence\Deployment;

use App\Domain\Deployment\Deployment;
use App\Domain\Deployment\DeploymentNotFoundException;
use App\Domain\Deployment\DeploymentRepository;
use PDO;

class SqLiteDeploymentRepository implements DeploymentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
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
        if ($query->rowCount() == 1) {
            $deployment->setId($this->connection->lastInsertId());
        }
        return $deployment;
    }

    /**
     * @param int $timeStampFrom
     * @param int $timeStampTo
     * @return array|Deployment[]
     */
    public function findAll(int $timeStampFrom, int $timeStampTo): array
    {
        $deployments = [];
        $query = $this->connection->prepare("SELECT * FROM deployments WHERE " .
        "time BETWEEN :timestamp_from AND :timestamp_to LIMIT 100");
        $query->execute([
            'timestamp_from' => $timeStampFrom,
            'timestamp_to' => $timeStampTo
        ]);
        $data = $query->fetchall(PDO::FETCH_ASSOC);
        foreach ($data as $n) {
            $deployments[] = $this->getDeploymentResult($n);
        }
        return $deployments;
    }

    /**
     * @param int $id
     * @return Deployment|null
     * @throws DeploymentNotFoundException
     */
    public function findDeploymentOfId(int $id): ?Deployment
    {
        $query = $this->connection->prepare("SELECT * FROM deployments WHERE id=:id");
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            throw new DeploymentNotFoundException("Deployment of $id not found");
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

    /**
     * @param string $application
     * @return array|Deployment[]
     */
    public function findDeploymentWithApplication(string $application): array
    {
        $deployments = [];
        $query = $this->connection
            ->prepare("SELECT * FROM deployments WHERE application=:application ORDER BY time");
        $query->execute(['application' => $application]);
        $data = $query->fetchall(PDO::FETCH_ASSOC);
        foreach ($data as $n) {
            $deployments[] = $this->getDeploymentResult($n);
        }
        return $deployments;
    }

    /**
     * @return string[]
     */
    public function findApplications(): array
    {
        $query = $this->connection
            ->prepare("SELECT DISTINCT application FROM deployments ORDER BY deployments.application");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_COLUMN, 0);
    }
}
