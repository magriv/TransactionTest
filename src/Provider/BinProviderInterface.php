<?php

namespace App\Provider;

use App\Exception\BinProviderException;

interface BinProviderInterface
{
    /**
     * @param string $bin
     *
     * @return string
     * @throws BinProviderException
     */
    public function getCountryCodeByBIN(string $bin): string;
}