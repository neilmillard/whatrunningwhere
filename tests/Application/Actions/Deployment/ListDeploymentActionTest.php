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
        $now = time();
        $before = strtotime('-1 day');
        $deploymentRepositoryProphecy = $this->prophesize(DeploymentRepository::class);
        $deploymentRepositoryProphecy
            ->findAll($before, $now)
            ->willReturn([$deployment])
            ->shouldBeCalledOnce();

        $container->set(DeploymentRepository::class, $deploymentRepositoryProphecy->reveal());

        $params = [
            'from' => $before,
            'to' => $now
        ];
        //When
        $request = $this->createRequest('GET', '/deployments', http_build_query($params));
        $response = $app->handle($request);

        $payload = (string)$response->getBody();

        //Then
        $expectedPayload = new ActionPayload(
            200,
            [
                'from' => "$before",
                'to' => "$now",
                'deployments' => [$deployment]
            ]
        );
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);
        $this->assertEquals($serializedPayload, $payload);
    }
}
