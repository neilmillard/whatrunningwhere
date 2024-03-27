<?php

declare(strict_types=1);

namespace App\Application\Actions\Deployment;

use App\Application\Actions\Action;
use App\Domain\Deployment\DeploymentRepository;
use Psr\Log\LoggerInterface;

abstract class DeploymentAction extends Action
{
    protected DeploymentRepository $deploymentRepository;

    public function __construct(LoggerInterface $logger, DeploymentRepository $deploymentRepository)
    {
        parent::__construct($logger);
        $this->deploymentRepository = $deploymentRepository;
    }
}
