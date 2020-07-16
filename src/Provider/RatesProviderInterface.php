<?php

namespace App\Provider;

use App\Exception\RatesProviderException;

interface RatesProviderInterface
{
    /**
     * @param string $currency
     *
     * @return string
     * @throws RatesProviderException
     */
    public function getRateByCurrency(string $currency): string;
}