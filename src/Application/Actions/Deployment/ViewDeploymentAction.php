<?php

namespace App\Application\Actions\Deployment;

use App\Application\Actions\Deployment\DeploymentAction;
use Psr\Http\Message\ResponseInterface as Response;

class ViewDeploymentAction extends DeploymentAction
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $deploymentId = (int)$this->resolveArg('id');
        $deployment = $this->deploymentRepository->findDeploymentOfId($deploymentId);

        $this->logger->info("Deployment of id `${deploymentId}` was viewed.");

        return $this->respondWithData($deployment);
    }
}
