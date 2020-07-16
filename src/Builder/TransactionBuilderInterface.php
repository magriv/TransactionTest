<?php

namespace App\Builder;

use App\Exception\BuildTransactionException;
use App\Model\Transaction;

interface TransactionBuilderInterface
{
    /**
     * @param mixed $data
     *
     * @return Transaction
     * @throws BuildTransactionException
     */
    public function build($data): Transaction;
}
