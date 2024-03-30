<?php

namespace App\Domain\Deployment;

use App\Domain\DomainException\DomainRecordNotFoundException;

class DeploymentNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The deployment you requested does not exist.';
}
