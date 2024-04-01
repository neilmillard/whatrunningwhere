<?php

namespace App\Application\Actions\Application;

use App\Application\Actions\Action;
use App\Domain\Application\ApplicationDeployment;
use App\Domain\Application\ApplicationDeploymentRepository;
use App\Domain\Deployment\DeploymentRepository;
use Psr\Log\LoggerInterface;

abstract class ApplicationAction extends Action
{
    protected DeploymentRepository $deploymentRepository;
    protected ApplicationDeploymentRepository $applicationDeploymentRepository;

    public function __construct(
        LoggerInterface $logger,
        DeploymentRepository $deploymentRepository,
        ApplicationDeploymentRepository $applicationDeploymentRepository
    ) {
        parent::__construct($logger);
        $this->deploymentRepository = $deploymentRepository;
        $this->applicationDeploymentRepository = $applicationDeploymentRepository;
    }
}
