<?php

namespace Tests\Application\Actions\Deployment;

use App\Application\Actions\ActionPayload;
use App\Application\Actions\Deployment\CreateDeploymentAction;
use App\Domain\Application\ApplicationDeploymentRepository;
use App\Domain\Deployment\Deployment;
use App\Domain\Deployment\DeploymentRepository;
use DI\Container;
use Prophecy\Argument;
use Tests\TestCase;

class CreateDeploymentActionTest extends TestCase
{
    public function testAction()
    {
        //Given
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $deployment = new Deployment(1, 'bill.gates', 'Frontend', '1.0.0', 'prod', null);
        $deployment->setId(1);

        $deploymentRepositoryProphecy = $this->prophesize(DeploymentRepository::class);
        $deploymentRepositoryProphecy
            ->create(Argument::type(Deployment::class))
            ->willReturn($deployment)
            ->shouldBeCalledOnce();

        $container->set(DeploymentRepository::class, $deploymentRepositoryProphecy->reveal());

        $applicationDeploymentRepositoryProphecy = $this->prophesize(ApplicationDeploymentRepository::class);
        $applicationDeploymentRepositoryProphecy
            ->findApplicationDeployments($applicationId)
            ->willReturn([$appDeployment, $appDeployment2]);
        $container->set(ApplicationDeploymentRepository::class, $applicationDeploymentRepositoryProphecy->reveal());

        //When
        $request = $this->createRequest('POST', '/deployments');
        $request = $request->withParsedBody($deployment->jsonSerialize());
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(201, $deployment);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        //Then
        $this->assertEquals($serializedPayload, $payload);
    }
}
