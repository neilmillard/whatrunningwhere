<?php

declare(strict_types=1);

use App\Application\Actions\Application\ListApplicationAction;
use App\Application\Actions\Deployment\CreateDeploymentAction;
use App\Application\Actions\Deployment\DisplayDeploymentFormAction;
use App\Application\Actions\Deployment\ListDeploymentAction;
use App\Application\Actions\Deployment\ViewDeploymentAction;
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

    $app->group('/deployments', function (Group $group) {
        $group->post('', CreateDeploymentAction::class);
        $group->get('', ListDeploymentAction::class);
        $group->get('/new', DisplayDeploymentFormAction::class);
        $group->get('/{id}', ViewDeploymentAction::class);
    });

    $app->group('/applications', function ($group) {
        $group->get('', ListApplicationAction::class);
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
