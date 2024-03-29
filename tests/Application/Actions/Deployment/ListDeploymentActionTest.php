<?php

namespace Tests\Application\Actions\Deployment;

use App\Application\Actions\ActionPayload;
use App\Domain\Deployment\Deployment;
use App\Domain\Deployment\DeploymentRepository;
use DI\Container;
use Tests\TestCase;

class ListDeploymentActionTest extends TestCase
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
            ->findAll()
            ->willReturn([$deployment])
            ->shouldBeCalledOnce();

        $container->set(DeploymentRepository::class, $deploymentRepositoryProphecy->reveal());

        //When
        $request = $this->createRequest('GET', '/deployments');
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(200, [$deployment]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        //Then
        $this->assertEquals($serializedPayload, $payload);
    }
}
