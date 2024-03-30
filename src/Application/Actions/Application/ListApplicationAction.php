<?php

namespace App\Application\Actions\Application;

use App\Application\Actions\Application\ApplicationAction;
use Psr\Http\Message\ResponseInterface as Response;

class ListApplicationAction extends ApplicationAction
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $deployments = $this->deploymentRepository->findApplications();
        $this->logger->info("Applications list was viewed.");
        return $this->respondWithData($deployments);
    }
}
