<?php

namespace App\Exceptions;

use Exception;

class TaskDependenciesNotCompletedException extends Exception
{
    protected $message = "Cannot complete this task until all dependencies are completed.";
}
