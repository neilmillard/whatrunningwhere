<?php

namespace App\Application\Actions\Deployment;

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
        $id = $deployment->getId();
        $this->logger->info("Deployment of id `${id}` was viewed.");

        return $this->respondWithData($deployment);
    }
}
