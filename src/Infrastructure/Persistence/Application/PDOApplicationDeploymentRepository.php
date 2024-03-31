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
    public function create(ApplicationDeployment $applicationDeployment): ApplicationDeployment
    {
        // TODO: Implement create() method.
    }

    /**
     * @inheritDoc
     */
    public function update(ApplicationDeployment $applicationDeployment): ApplicationDeployment
    {
        // TODO: Implement update() method.
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
}
