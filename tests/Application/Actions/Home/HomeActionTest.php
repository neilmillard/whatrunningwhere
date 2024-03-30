<?php

namespace Tests\Application\Actions\Home;

use DI\Container;
use Tests\TestCase;

class HomeActionTest extends TestCase
{
    public function testAction()
    {
        //Given
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        //When
        $request = $this->createRequest('GET', '/');
        $response = $app->handle($request);

        $payload = (string)$response->getBody();

        //Then
        $this->assertStringContainsString('Hello', $payload);
    }
}
