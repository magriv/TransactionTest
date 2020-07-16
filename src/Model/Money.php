<?php

namespace App\Model;

class Money
{
    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    public function __construct(string $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
