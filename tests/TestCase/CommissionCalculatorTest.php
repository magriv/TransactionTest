<?php

namespace Tests\TestCase;

use App\Calculator\CommissionCalculator;
use App\Model\Money;
use App\Model\Transaction;
use App\Provider\BinProviderInterface;
use App\Provider\RatesProviderInterface;
use App\Calculator\BcmathCalculator;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    /**
     * @var CommissionCalculator
     */
    private $commissionCalculator;

    protected function setUp(): void
    {
        $mathCalculator = new BcmathCalculator();

        $binProvider = $this->createMock(BinProviderInterface::class);
        $binProvider
            ->method('getCountryCodeByBIN')
            ->willReturnMap($this->getBINResponseMap());

        $ratesProvider = $this->createMock(RatesProviderInterface::class);
        $ratesProvider
            ->method('getRateByCurrency')
            ->willReturnMap($this->getRatesResponseMap());

        $this->commissionCalculator = new CommissionCalculator($binProvider, $ratesProvider, $mathCalculator);
    }

    /**
     * @dataProvider transactionDataProvider
     *
     * @param Transaction $transaction
     * @param string      $expectedCommissionValue
     */
    public function testCommissionCalculatedValue(Transaction $transaction, string $expectedCommissionValue): void
    {
        $commission = $this->commissionCalculator->calculate($transaction);

        self::assertSame($expectedCommissionValue, $commission);
    }

    public function transactionDataProvider(): array
    {
        return [
            [
                new Transaction('45717360', new Money('100.00', 'EUR')),
                '1',
            ],
            [
                new Transaction('516793', new Money('50.00', 'USD')),
                '0.44',
            ],
            [
                new Transaction('45417360', new Money('10000.00', 'JPY')),
                '1.64',
            ],
            [
                new Transaction('41417360', new Money('130.00', 'USD')),
                '2.28',
            ],
            [
                new Transaction('4745030', new Money('2000.00', 'GBP')),
                '44.02',
            ],
        ];
    }

    private function getBINResponseMap(): array
    {
        return [
            ['45717360', 'DK'],
            ['516793', 'LT'],
            ['45417360', 'JP'],
            ['41417360', 'US'],
            ['4745030', 'GB'],
        ];
    }

    private function getRatesResponseMap(): array
    {
        return [
            ['EUR', '0'],
            ['CAD', '1.5452'],
            ['HKD', '8.851'],
            ['ISK', '160'],
            ['PHP', '56.499'],
            ['DKK', '7.4452'],
            ['HUF', '354.08'],
            ['CZK', '26.693'],
            ['AUD', '1.6338'],
            ['RON', '4.8433'],
            ['SEK', '10.35'],
            ['IDR', '16692.98'],
            ['INR', '85.8555'],
            ['BRL', '6.1154'],
            ['RUB', '81.1888'],
            ['HRK', '7.5356'],
            ['JPY', '122.24'],
            ['THB', '36.148'],
            ['CHF', '1.0787'],
            ['SGD', '1.5888'],
            ['PLN', '4.4928'],
            ['BGN', '1.9558'],
            ['TRY', '7.832'],
            ['CNY', '7.9861'],
            ['NOK', '10.6148'],
            ['NZD', '1.7439'],
            ['ZAR', '19.0181'],
            ['USD', '1.1414'],
            ['MXN', '25.5051'],
            ['ILS', '3.9207'],
            ['GBP', '0.90875'],
            ['KRW', '1373.74'],
            ['MYR', '4.8755'],
        ];
    }
}
