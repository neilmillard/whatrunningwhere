<?php

namespace Tests\Application\Actions\Deployment;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Deployment\Deployment;
use App\Domain\Deployment\DeploymentNotFoundException;
use App\Domain\Deployment\DeploymentRepository;
use DI\Container;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class ViewDeploymentActionTest extends TestCase
{
    public function testAction()
    {
        //Given
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();
        $deployment = new Deployment(1, 'bill.gates', 'Frontend', '1.0.0', 'prod', 1);

        $deploymentRepositoryProphecy = $this->prophesize(DeploymentRepository::class);
        $deploymentRepositoryProphecy
            ->findDeploymentOfId(1)
            ->willReturn($deployment)
            ->shouldBeCalledOnce();

        $container->set(DeploymentRepository::class, $deploymentRepositoryProphecy->reveal());

        //When
        $request = $this->createRequest('GET', '/deployments/1');
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(200, $deployment);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        //Then
        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsDeploymentNotFoundException()
    {
        //Given
        $app = $this->getAppInstance();

        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $app->add($errorMiddleware);

        /** @var Container $container */
        $container = $app->getContainer();

        $deploymentRepositoryProphecy = $this->prophesize(DeploymentRepository::class);
        $deploymentRepositoryProphecy
            ->findDeploymentOfId(1)
            ->willThrow(new DeploymentNotFoundException())
            ->shouldBeCalledOnce();

        $container->set(DeploymentRepository::class, $deploymentRepositoryProphecy->reveal());

        //When
        $request = $this->createRequest('GET', '/deployments/1');
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'The deployment you requested does not exist.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        //Then
        $this->assertEquals($serializedPayload, $payload);
    }
}
