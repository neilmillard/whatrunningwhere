<?php

namespace App\Application\Actions\Deployment;

use Psr\Http\Message\ResponseInterface as Response;

class ListDeploymentAction extends DeploymentAction
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $deployments = $this->deploymentRepository->findAll();
        $this->logger->info("Deployments list was viewed.");
        return $this->respondWithData($deployments);
    }
}
