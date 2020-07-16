<?php

namespace App\Logger;

class EchoLogger implements LoggerInterface
{
    public function log(string $data): void
    {
        echo $data . "\n";
    }
}
