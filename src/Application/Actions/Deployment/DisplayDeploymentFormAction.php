<?php

namespace App\Application\Actions\Deployment;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class DisplayDeploymentFormAction extends DeploymentAction
{
    /**
     * @inheritDoc
     */
    protected function action(): Response
    {
        $view = Twig::fromRequest($this->request);
        /** @noinspection PhpUnhandledExceptionInspection */
        return $view->render($this->response, 'deployment_form.html');
    }
}
