<?php

namespace App\Logger;

interface LoggerInterface
{
    public function log(string $data): void;
}
