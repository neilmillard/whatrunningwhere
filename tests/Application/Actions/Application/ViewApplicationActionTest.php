<?php

namespace Tests\Application\Actions\Application;

use App\Application\Actions\ActionPayload;
use App\Domain\Application\Application;
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
        $deployment = new Deployment(1, 'bill.gates', 'frontend', '1.0.4', 'test', 1);
        $deployment2 = new Deployment(3, 'bill.gates', 'frontend', '1.0.3', 'prod', 2);
        $deployment3 = new Deployment(8, 'bill.gates', 'frontend', '1.0.4', 'prod', 3);


        $deploymentRepositoryProphecy = $this->prophesize(DeploymentRepository::class);
        $deploymentRepositoryProphecy
            ->findDeploymentWithApplication($deployment->getApplication())
            ->willReturn([$deployment,$deployment2,$deployment3])
            ->shouldBeCalledOnce();

        $container->set(DeploymentRepository::class, $deploymentRepositoryProphecy->reveal());

        //When
        $request = $this->createRequest('GET', '/applications/frontend');
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $applicationPayload = new Application($deployment->getApplication());
        $applicationPayload->addDeployment($deployment);
        $applicationPayload->addDeployment($deployment3);
        $expectedPayload = new ActionPayload(200, $applicationPayload);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        //Then
        $this->assertEquals($serializedPayload, $payload);
    }
}
