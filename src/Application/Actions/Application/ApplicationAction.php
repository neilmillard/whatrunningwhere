<?php

namespace App\Application\Actions\Application;

use App\Application\Actions\Action;
use App\Domain\Deployment\DeploymentRepository;
use Psr\Log\LoggerInterface;

abstract class ApplicationAction extends Action
{
    protected DeploymentRepository $deploymentRepository;

    public function __construct(LoggerInterface $logger, DeploymentRepository $deploymentRepository)
    {
        parent::__construct($logger);
        $this->deploymentRepository = $deploymentRepository;
    }
}
