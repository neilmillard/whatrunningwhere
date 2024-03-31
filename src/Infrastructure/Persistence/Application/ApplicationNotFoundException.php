<?php

namespace App\Infrastructure\Persistence\Application;

use App\Domain\DomainException\DomainRecordNotFoundException;

class ApplicationNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The application you requested does not exist.';
}
