<?php

namespace Tests\Application\Actions\Deployment;

use DI\Container;
use Tests\TestCase;

class DisplayDeploymentFormActionTest extends TestCase
{
    public function testAction()
    {
        //Given
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        //When
        $request = $this->createRequest('GET', '/deployments/new');
        $response = $app->handle($request);

        $payload = (string)$response->getBody();

        //Then
        $this->assertStringContainsString('form action="/deployments" method="post"', $payload);
        $this->assertStringContainsString('submit', $payload);
    }
}
