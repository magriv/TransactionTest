<?php

namespace App\Model;

class Transaction
{
    /**
     * @var string
     */
    private $bin;

    /**
     * @var Money
     */
    private $total;

    public function __construct(string $bin, Money $total)
    {
        $this->bin = $bin;
        $this->total = $total;
    }

    public function getBin(): string
    {
        return $this->bin;
    }

    public function getTotal(): Money
    {
        return $this->total;
    }
}