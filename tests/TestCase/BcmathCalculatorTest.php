<?php

namespace Tests\TestCase;

use App\Calculator\CommissionCalculator;
use App\Model\Money;
use App\Model\Transaction;
use App\Provider\BinProviderInterface;
use App\Provider\RatesProviderInterface;
use App\Calculator\BcmathCalculator;
use PHPUnit\Framework\TestCase;

class BcmathCalculatorTest extends TestCase
{
    /**
     * @var BcmathCalculator
     */
    private $calculator;

    protected function setUp(): void
    {
        $this->calculator = new BcmathCalculator();
    }

    /**
     * @dataProvider divideDataProvider
     *
     * @param string $number1
     * @param string $number2
     * @param string $expected
     */
    public function testDivide(string $number1, string $number2, string $expected): void
    {
        $result = $this->calculator->divide($number1, $number2);

        self::assertSame($expected, $result);
    }

    /**
     * @dataProvider multiplyDataProvider
     *
     * @param string $number1
     * @param string $number2
     * @param string $expected
     */
    public function testMultiply(string $number1, string $number2, string $expected): void
    {
        $result = $this->calculator->multiply($number1, $number2);

        self::assertSame($expected, $result);
    }

    /**
     * @dataProvider ceilDataProvider
     *
     * @param string $number
     * @param int    $precision
     * @param string $expected
     */
    public function testCeil(string $number, int $precision, string $expected): void
    {
        $result = $this->calculator->ceil($number, $precision);

        self::assertSame($expected, $result);
    }

    public function divideDataProvider(): array
    {
        return [
            ['1', '1', '1.00000000000000'],
            ['1.00', '2', '0.50000000000000'],
            ['2', '3', '0.66666666666666'],
            ['7', '-2.2', '-3.18181818181818'],
            ['100.23', '45.23', '2.21600707495025'],
        ];
    }

    public function multiplyDataProvider(): array
    {
        return [
            ['1', '1', '1.00000000000000'],
            ['-1.00', '2', '-2.00000000000000'],
            ['2.15', '3', '6.45000000000000'],
            ['100.23', '45.23', '4533.40290000000000'],
            ['242.4353453454356', '556.345435624705335', '134877.79781703224777'],
        ];
    }

    public function ceilDataProvider(): array
    {
        return [
            ['1', 2, '1'],
            ['2.17862483', 3, '2.179'],
            ['2.17143534', 3, '2.172'],
            ['3.17143534', 2, '3.18'],
            ['3.17643534', 2, '3.18'],
            ['-4.873246', 4, '-4.8733'],
            ['242.4353453454356', 8, '242.43534535'],
        ];
    }
}
