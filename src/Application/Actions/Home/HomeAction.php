<?php

namespace App\Application\Actions\Home;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class HomeAction extends Action
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $view = Twig::fromRequest($this->request);
        /** @noinspection PhpUnhandledExceptionInspection */
        return $view->render($this->response, 'hello.html', [
            'name' => 'Controller'
        ]);
    }
}
