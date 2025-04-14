<?php

namespace Backoffice\Core\Exceptions;

use Backoffice\Core\Logger;

/**
 * Exception thrown when a route configuration is invalid.
 */
class InvalidRouteException extends \InvalidArgumentException
{
    public function logError() { 
        $logger = new Logger();
        $logger->logException();
    }
}