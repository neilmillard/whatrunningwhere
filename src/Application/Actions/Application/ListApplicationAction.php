<?php

namespace App\Application\Actions\Application;

use Psr\Http\Message\ResponseInterface as Response;

class ListApplicationAction extends ApplicationAction
{
    /**
     * @inheritDoc
     * @OA\Get (
     *      tags={"applicationDeployment"},
     *      path="/applications",
     *      operationId="listApplicationDeployments",
     *      @OA\Response(
     *          response=200,
     *          description="A list of all applicationDeployment records",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/ApplicationDeployment")
     *          )
     *      )
     *  )
     */
    protected function action(): Response
    {
        $deployments = $this->deploymentRepository->findApplications();
        $this->logger->info("Applications list was viewed.");
        return $this->respondWithData($deployments);
    }
}
