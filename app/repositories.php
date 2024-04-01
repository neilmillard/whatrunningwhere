<?php

declare(strict_types=1);

use App\Domain\Application\ApplicationDeploymentRepository;
use App\Domain\Deployment\DeploymentRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\Application\PDOApplicationDeploymentRepository;
use App\Infrastructure\Persistence\Deployment\SqLiteDeploymentRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => autowire(InMemoryUserRepository::class),
        DeploymentRepository::class => autowire(SqLiteDeploymentRepository::class),
        ApplicationDeploymentRepository::class => autowire(PDOApplicationDeploymentRepository::class),
    ]);
};
