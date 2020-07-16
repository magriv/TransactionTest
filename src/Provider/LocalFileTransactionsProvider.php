<?php

namespace App\Provider;

use App\Builder\TransactionBuilderInterface;
use App\Exception\BuildTransactionException;
use App\Exception\ReadTransactionException;
use App\Model\Transaction;

class LocalFileTransactionsProvider implements TransactionsProviderInterface
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var TransactionBuilderInterface
     */
    private $transactionBuilder;

    public function __construct(string $filename, TransactionBuilderInterface $transactionBuilder)
    {
        $this->filename = $filename;
        $this->transactionBuilder = $transactionBuilder;
    }

    /**
     * @return Transaction[]
     * @throws BuildTransactionException
     * @throws ReadTransactionException
     */
    public function getAllTransactions(): iterable
    {
        try {
            $file = new \SplFileObject($this->filename);
        } catch (\RuntimeException|\LogicException $e) {
            throw new ReadTransactionException(sprintf('Error occurred while opening the file %s', $this->filename), $e->getCode(), $e);
        }

        while (!$file->eof()) {
            $line = $file->fgets();
            if ($line === false) {
                throw new ReadTransactionException(sprintf('Error occurred while reading the file %s', $this->filename));
            }
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            yield $this->transactionBuilder->build($line);
        }
    }
}
