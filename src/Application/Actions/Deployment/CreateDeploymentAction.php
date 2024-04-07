<?php

namespace App\Application\Actions\Deployment;

use App\Domain\Deployment\Deployment;
use Psr\Http\Message\ResponseInterface as Response;

class CreateDeploymentAction extends DeploymentAction
{
    /**
     * @inheritDoc
     * @OA\Post(
     *     path="/deployments",
     *     tags={"deployment"},
     *     operationId="createDeployment",
     *     description="Creates a deployment entry",
     *     @OA\RequestBody(
     *         description="Deployment object to be created",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Deployment"),
     *         @OA\MediaType(
     *             mediaType="application\x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Deployment")
     *         )
     *     ),
     *     @OA\Response(
     *          response=405,
     *          description="Invalid input",
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Created Okay",
     *      ),
     * )
     */
    protected function action(): Response
    {
        // Validate and sanitize the input data
        // Get all POST parameters
        $data = (array)$this->getFormData();
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
        $this->logger->info("Deployment was created. `$myDeployment`");
        $deployment = $this->deploymentRepository->create($myDeployment);
        $deploymentId = $deployment->getId();
        $this->logger->info("Deployment of id `$deploymentId` was saved.");

        return $this->respondWithData($deployment, 201);
    }
}
