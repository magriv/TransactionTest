<?php

namespace App;

use App\Logger\LoggerInterface;
use App\Provider\TransactionsProviderInterface;
use App\Calculator\CommissionCalculator;
use App\Exception\BinProviderException;
use App\Exception\RatesProviderException;

class TransactionsProcessor
{
    /**
     * @var TransactionsProviderInterface
     */
    private $transactionsProvider;

    /**
     * @var CommissionCalculator
     */
    private $commissionCalculator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(TransactionsProviderInterface $transactionsProvider, CommissionCalculator $commissionCalculator, LoggerInterface $logger)
    {
        $this->transactionsProvider = $transactionsProvider;
        $this->commissionCalculator = $commissionCalculator;
        $this->logger = $logger;
    }

    /**
     * @throws BinProviderException
     * @throws RatesProviderException
     */
    public function processTransactions(): void
    {
        $transactions = $this->transactionsProvider->getAllTransactions();
        foreach ($transactions as $transaction) {
            $result = $this->commissionCalculator->calculate($transaction);

            $this->logger->log($result);
        }
    }
}