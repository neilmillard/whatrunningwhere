<?php

namespace App\Application\Controllers;

use App\Application\Models\DeploymentModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class DeploymentController
{
    private DeploymentModel $deploymentModel;
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->deploymentModel = new DeploymentModel();
    }

    public function displayDeploymentForm(
        ServerRequestInterface $request,
        ResponseInterface      $response,
        array                  $args
    ): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'deployment_form.html');
    }

    public function createDeployment(
        ServerRequestInterface $request,
        ResponseInterface      $response,
        array                  $args
    ): ResponseInterface
    {
        // Validate and sanitize the input data
        // Get all POST parameters
        $data = (array)$request->getParsedBody();
        $time = date("Y-m-d H:i:s");
        $application = isset($data['application']) ? trim($data['application']) : '';
        $version = isset($data['version']) ? trim($data['version']) : '';
        $environment = isset($data['environment']) ? trim($data['environment']) : '';
        // Create a new deployment
        $this->deploymentModel->createDeployment($time, $application, $version, $environment);
        // redirect to deployments
        return $response
            ->withHeader('Location', '/deployments')
            ->withStatus(302);
    }

    public function displayDeployments(
        ServerRequestInterface $request,
        ResponseInterface      $response,
        array                  $args
    ): ResponseInterface {
        // Get the current deployed versions
        $deployments = $this->deploymentModel->getDeployments();
        $view = Twig::fromRequest($request);
        return $view->render($response, 'deployment_list.html', [
            'deployments' => $deployments
        ]);
    }
}
