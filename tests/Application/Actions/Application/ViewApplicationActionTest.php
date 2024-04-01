<?php

namespace Tests\Application\Actions\Application;

use App\Application\Actions\ActionPayload;
use App\Domain\Application\Application;
use App\Domain\Application\ApplicationDeployment;
use App\Domain\Application\ApplicationDeploymentRepository;
use App\Domain\Deployment\Deployment;
use App\Domain\Deployment\DeploymentRepository;
use DI\Container;
use Tests\TestCase;

class ViewApplicationActionTest extends TestCase
{
    public function testAction()
    {
        //Given
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();
        $applicationId = 'frontend';
        $appDeployment = new ApplicationDeployment($applicationId, 'test', 1);
        $appDeployment2 = new ApplicationDeployment($applicationId, 'prod', 3);

        $applicationDeploymentRepositoryProphecy = $this->prophesize(ApplicationDeploymentRepository::class);
        $applicationDeploymentRepositoryProphecy
            ->findApplicationDeployments($applicationId)
            ->willReturn([$appDeployment, $appDeployment2]);
        $container->set(ApplicationDeploymentRepository::class, $applicationDeploymentRepositoryProphecy->reveal());

        $deployment = new Deployment(1, 'bill.gates', 'frontend', '1.0.4', 'test', 1);
        $deployment2 = new Deployment(3, 'bill.gates', 'frontend', '1.0.3', 'prod', 2);
        $deployment3 = new Deployment(8, 'bill.gates', 'frontend', '1.0.4', 'prod', 3);
        $deploymentRepositoryProphecy = $this->prophesize(DeploymentRepository::class);
        $deploymentRepositoryProphecy
            ->findDeploymentOfId($deployment->getId())
            ->willReturn($deployment)
            ->shouldBeCalledOnce();
        $deploymentRepositoryProphecy
            ->findDeploymentOfId($deployment3->getId())
            ->willReturn($deployment3)
            ->shouldBeCalledOnce();

        $container->set(DeploymentRepository::class, $deploymentRepositoryProphecy->reveal());

        //When
        $request = $this->createRequest('GET', '/applications/frontend');
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $applicationPayload = new Application($applicationId);
        $applicationPayload->addDeployment($deployment);
        $applicationPayload->addDeployment($deployment3);
        $expectedPayload = new ActionPayload(200, $applicationPayload);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        //Then
        $this->assertEquals($serializedPayload, $payload);
    }
}
