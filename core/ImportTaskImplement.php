<?php

namespace Core;

use Core\Interfaces\ImportTask;

abstract class ImportTaskImplement implements ImportTask
{
    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    abstract public function startImport();
}
