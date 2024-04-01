<?php

namespace App\Application\Actions\Deployment;

use Psr\Http\Message\ResponseInterface as Response;

class ListDeploymentAction extends DeploymentAction
{
    /**
     * @inheritDoc
     * @OA\Get (
     *      tags={"deployment"},
     *      path="/deployments",
     *      operationId="listDeployments",
     *      @OA\Response(
     *          response=200,
     *          description="A list of all deployment event records",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Deployment")
     *          )
     *      )
     *  )
     */
    protected function action(): Response
    {
        $deployments = $this->deploymentRepository->findAll();
        $this->logger->info("Deployments list was viewed.");
        return $this->respondWithData($deployments);
    }
}
