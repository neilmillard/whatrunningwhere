<?php

namespace App\Application\Actions\Deployment;

use Psr\Http\Message\ResponseInterface as Response;

class ViewDeploymentAction extends DeploymentAction
{
    /**
     * @inheritDoc
     * @OA\Get (
     *     tags={"deployment"},
     *     path="/deployments/{id}",
     *     operationId="getDeployment",
     *     @OA\Parameter (
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Deployment id",
     *         @OA\Schema (
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A single deployment event record",
     *         @OA\JsonContent(ref="#/components/schemas/Deployment")
     *     )
     * )
     */
    protected function action(): Response
    {
        $deploymentId = (int)$this->resolveArg('id');
        $deployment = $this->deploymentRepository->findDeploymentOfId($deploymentId);
        $id = $deployment->getId();
        $this->logger->info("Deployment of id `$id` was viewed.");

        return $this->respondWithData($deployment);
    }
}
