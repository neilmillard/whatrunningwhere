<?php

namespace App\Application\Actions\Deployment;

use App\Application\Actions\Deployment\DeploymentAction;
use App\Domain\Deployment\Deployment;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;

class CreateDeploymentAction extends DeploymentAction
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        // Validate and sanitize the input data
        // Get all POST parameters
        $data = (array)$this->request->getParsedBody();
        $time = time();
        $application = isset($data['application']) ? trim($data['application']) : '';
        $version = isset($data['version']) ? trim($data['version']) : '';
        $environment = isset($data['environment']) ? trim($data['environment']) : '';
        $who = isset($data['who']) ? trim($data['who']) : '';
        // Create a new deployment
        $myDeployment = new Deployment(
            $time,
            $who,
            $application,
            $version,
            $environment,
            null,
        );
        $this->logger->info("Deployment was created. `${myDeployment}`");
        $deployment = $this->deploymentRepository->create($myDeployment);
        $deploymentId = $deployment->getId();
        $this->logger->info("Deployment of id `${deploymentId}` was saved.");

        return $this->respondWithData($deployment, 201);
    }
}
