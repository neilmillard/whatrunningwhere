<?php

declare(strict_types=1);

use App\Application\Actions\Deployment\CreateDeploymentAction;
use App\Application\Actions\Deployment\DisplayDeploymentFormAction;
use App\Application\Actions\Deployment\ListDeploymentsAction;
use App\Application\Actions\Home\HomeAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', HomeAction::class);

    $app->get('/deployments', ListDeploymentsAction::class);
    $app->post('/deployments', CreateDeploymentAction::class);
    $app->get('/deployments/new', DisplayDeploymentFormAction::class);

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
