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
    public function findApplicationDeployment(string $name, string $environment): ApplicationDeployment
    {
        $query = $this->connection
            ->prepare("SELECT * FROM deployments 
         WHERE application=:name AND environment=:environment ORDER BY time LIMIT 1");
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
            $result['application'],
            $result['environment'],
            $result['id']
        );
    }

    /**
     * @param  string $applicationId
     * @return ApplicationDeployment[]
     * @throws ApplicationNotFoundException
     */
    public function findApplicationDeployments(string $applicationId): array
    {
        // The sub-query selects rows filtered by application name, and sorting the environments.
        // these rows get a row_num
        //
        // The outer query selects from the sub-query but only includes rows where row_num is 1. Ie the latest entry
        // for each environment
        $query = $this->connection
            ->prepare(
                "SELECT application, environment, time, id
      FROM (SELECT application,
                   environment,
                   time,
                   id,
                   ROW_NUMBER() OVER (PARTITION BY application, environment ORDER BY time DESC) AS row_num
            FROM deployments
            WHERE application = :name) AS subquery
      WHERE row_num = 1"
            );
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
