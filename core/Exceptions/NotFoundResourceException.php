<?php
namespace Core\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class NotFoundResourceException extends ModelNotFoundException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
