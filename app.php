<?php
require __DIR__.'/vendor/autoload.php';

$httpClient = Symfony\Component\HttpClient\HttpClient::create();
$binProvider = new \App\Provider\BinlistBinProvider($httpClient);
$ratesProvider = new \App\Provider\ExchangeratesapiRatesProvider($httpClient);
$mathCalculator = new \App\Calculator\BcmathCalculator();
$commissionCalculator = new \App\Calculator\CommissionCalculator($binProvider, $ratesProvider, $mathCalculator);

$transactionBuilder = new \App\Builder\FromJSONTransactionBuilder();
$transactionsProvider = new \App\Provider\LocalFileTransactionsProvider($argv[1], $transactionBuilder);
$logger = new \App\Logger\EchoLogger();

$app = new \App\TransactionsProcessor($transactionsProvider, $commissionCalculator, $logger);
$app->processTransactions();
