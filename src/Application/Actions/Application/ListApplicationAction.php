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
     *      operationId="listApplications",
     *      @OA\Response(
     *          response=200,
     *          description="A list of all application entries",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(type="string")
     *          )
     *      )
     *  )
     */
    protected function action(): Response
    {
        $applications = $this->deploymentRepository->findApplications();
        $this->logger->info("Applications list was viewed.");
        return $this->respondWithData($applications);
    }
}
