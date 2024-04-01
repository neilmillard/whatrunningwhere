<?php

namespace App\Infrastructure\Persistence\Application;

use App\Domain\Application\ApplicationDeployment;
use App\Domain\Application\ApplicationDeploymentRepository;
use PDO;

class PDOApplicationDeploymentRepository implements ApplicationDeploymentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    /**
     * @inheritDoc
     */
    public function create(ApplicationDeployment $applicationDeployment): ?ApplicationDeployment
    {
        $data = [
            'name' => $applicationDeployment->getName(),
            'environment' => $applicationDeployment->getEnvironment(),
            'deployment_id' => $applicationDeployment->getDeployment(),
        ];
        $sql = "INSERT INTO applications (name, environment, deployment_id) "
            . "VALUES (:name, :environment, :deployment_id)";
        $query = $this->connection->prepare($sql);
        $query->execute($data);
        if ($query->rowCount() == 0) {
            return null;
        }
        return $applicationDeployment;
    }

    /**
     * @inheritDoc
     */
    public function updateOrCreate(ApplicationDeployment $applicationDeployment): ApplicationDeployment
    {
        $data = [
            'name' => $applicationDeployment->getName(),
            'environment' => $applicationDeployment->getEnvironment(),
            'deployment_id' => $applicationDeployment->getDeployment(),
        ];
        $sql = "UPDATE applications SET deployment_id=:deployment_id "
            . "WHERE name=:name AND environment=:environment";
        $query = $this->connection->prepare($sql);
        $query->execute($data);
        if ($query->rowCount() == 0) {
            return $this->create($applicationDeployment);
        }
        return $applicationDeployment;
    }

    /**
     * @inheritDoc
     */
    public function findApplicationDeployment(string $name, string $environment): ApplicationDeployment
    {
        $query = $this->connection
            ->prepare("SELECT * FROM applications WHERE name=:name AND environment=:environment");
        $query->execute(['name' => $name, 'environment' => $environment]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            throw new ApplicationNotFoundException("Application $name in $environment not found");
        }
        return $this->getApplicationDeploymentFromResult($result);
    }

    /**
     * @param mixed $result
     * @return ApplicationDeployment
     */
    private function getApplicationDeploymentFromResult(mixed $result): ApplicationDeployment
    {
        return new ApplicationDeployment(
            $result['name'],
            $result['environment'],
            $result['deployment_id']
        );
    }

    /**
     * @throws ApplicationNotFoundException
     */
    public function findApplicationDeployments(string $applicationId): array
    {
        $query = $this->connection
            ->prepare("SELECT * FROM applications WHERE name=:name");
        $query->execute(['name' => $applicationId]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!$results) {
            throw new ApplicationNotFoundException("Application $applicationId not found");
        }
        $applicationDeployments = [];
        foreach ($results as $result) {
            $applicationDeployments[] = $this->getApplicationDeploymentFromResult($result);
        }
        return $applicationDeployments;
    }
}
