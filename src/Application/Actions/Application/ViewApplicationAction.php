<?php

namespace App\Application\Actions\Application;

use App\Domain\Application\ApplicationFactory;
use Psr\Http\Message\ResponseInterface as Response;

class ViewApplicationAction extends ApplicationAction
{
    /**
     * @inheritDoc
     * @OA\Get (
     *      tags={"applicationDeployment"},
     *       path="/applications/{name}",
     *      operationId="getApplication",
     *      @OA\Parameter (
     *          name="name",
     *          in="path",
     *          required=true,
     *          description="Application name",
     *          @OA\Schema (
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="A single application record",
     *          @OA\JsonContent(ref="#/components/schemas/Application")
     *      )
     *  )
     */
    protected function action(): Response
    {
        $applicationId = $this->resolveArg('application');
        $this->logger->info("Application $applicationId was viewed.");
        $application = ApplicationFactory::getApplication(
            $applicationId,
            $this->applicationDeploymentRepository,
            $this->deploymentRepository
        );
        return $this->respondWithData($application);
    }
}
