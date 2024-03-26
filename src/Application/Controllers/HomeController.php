<?php

namespace App\Application\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class HomeController
{
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        // your code to access items in the container... $this->container->get('');
        $view = Twig::fromRequest($request);
        return $view->render($response, 'hello.html', [
            'name' => 'Controller'
        ]);
    }

}