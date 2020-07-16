<?php

namespace App\Builder;

use App\Exception\BuildTransactionException;
use App\Model\Money;
use App\Model\Transaction;

class FromJSONTransactionBuilder implements TransactionBuilderInterface
{
    /**
     * @param string $data
     *
     * @return Transaction
     * @throws BuildTransactionException
     */
    public function build($data): Transaction
    {
        $decodedData = json_decode($data, true);

        if (!isset($decodedData['bin'], $decodedData['amount'], $decodedData['currency'])) {
            throw new BuildTransactionException(sprintf("Can't decode string '%s' from JSON", $data));
        }

        $total = new Money($decodedData['amount'], $decodedData['currency']);

        return new Transaction(
            $decodedData['bin'],
            $total
        );
    }
}
