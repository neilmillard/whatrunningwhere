<?php

namespace App\Application\Actions\Application;

use App\Domain\Application\ApplicationFactory;
use Psr\Http\Message\ResponseInterface as Response;

class ViewApplicationAction extends ApplicationAction
{
    /**
     * @inheritDoc
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
