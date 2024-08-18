<?php

namespace Tests\Application\ResponseEmitter;

use App\Application\ResponseEmitter\ResponseEmitter;
use Tests\Assets\HeaderStack;
use Tests\TestCase;

class ResponseEmitterTest extends TestCase
{
    public function setUp(): void
    {
        HeaderStack::reset();
    }

    public function tearDown(): void
    {
        HeaderStack::reset();
    }

    public function testEmit()
    {
        $response = $this->createResponse();
        $response->getBody()->write('Hello');

        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);

        $expectedStack = [
            ['header' => 'Access-Control-Allow-Credentials: true', 'replace' => true, 'status_code' => null],
            ['header' => 'Access-Control-Allow-Origin: ', 'replace' => true, 'status_code' => null],
            ['header' => 'Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization',
                'replace' => true, 'status_code' => null],
            ['header' => 'Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS', 'replace' => true,
                'status_code' => null],
            ['header' => 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0', 'replace' => true,
                'status_code' => null],
            ['header' => 'Cache-Control: post-check=0, pre-check=0', 'replace' => false, 'status_code' => null],
            ['header' => 'Pragma: no-cache', 'replace' => true, 'status_code' => null],
            ['header' => 'HTTP/1.1 200 ', 'replace' => true, 'status_code' => 200],
        ];
        $this->assertSame($expectedStack, HeaderStack::stack());
    }
}
