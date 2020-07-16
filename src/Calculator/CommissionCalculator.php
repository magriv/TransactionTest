<?php

namespace App\Calculator;

use App\Exception\BinProviderException;
use App\Exception\RatesProviderException;
use App\Model\Transaction;
use App\Provider\BinProviderInterface;
use App\Provider\RatesProviderInterface;

class CommissionCalculator
{
    private const CEIL_PRECISION = 2;
    private const EUR = 'EUR';
    private const EU_COUNTRY_CODES = [ 'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR',
        'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'];

    /**
     * @var BinProviderInterface
     */
    private $binProvider;

    /**
     * @var RatesProviderInterface
     */
    private $ratesProvider;

    /**
     * @var MathCalculatorInterface
     */
    private $mathCalculator;

    public function __construct(
        BinProviderInterface $binProvider,
        RatesProviderInterface $ratesProvider,
        MathCalculatorInterface $mathCalculator
    ) {
        $this->binProvider = $binProvider;
        $this->ratesProvider = $ratesProvider;
        $this->mathCalculator = $mathCalculator;
    }

    /**
     * @param Transaction $transaction
     *
     * @return string
     * @throws BinProviderException
     * @throws RatesProviderException
     */
    public function calculate(Transaction $transaction): string
    {
        $countryCode = $this->binProvider->getCountryCodeByBIN($transaction->getBin());
        $rate = $this->ratesProvider->getRateByCurrency($transaction->getTotal()->getCurrency());

        return $this->calculateValue($transaction, $countryCode, $rate);
    }

    private function calculateValue(Transaction $transaction, string $countryCode, $rate): string
    {
        $result = $transaction->getTotal()->getAmount();
        if (!$this->mathCalculator->isZero($rate) || $transaction->getTotal()->getCurrency() !== self::EUR) {
            $result = $this->mathCalculator->divide($result, $rate);
        }

        $result = $this->mathCalculator->multiply($result, $this->getCoefficientByCountryCode($countryCode));

        return $this->mathCalculator->ceil($result, self::CEIL_PRECISION);
    }

    private function getCoefficientByCountryCode(string $countryCode): string
    {
        return $this->isEuCountry($countryCode) ? '0.01' : '0.02';
    }

    private function isEuCountry(string $countryCode): bool
    {
        return in_array($countryCode, self::EU_COUNTRY_CODES, true);
    }
}