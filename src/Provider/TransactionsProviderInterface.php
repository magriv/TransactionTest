<?php

namespace App\Provider;

use App\Model\Transaction;

interface TransactionsProviderInterface
{
    /**
     * @return Transaction[]
     */
    public function getAllTransactions(): iterable;
}