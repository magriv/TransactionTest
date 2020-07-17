<?php

namespace App\Calculator;

class BcmathCalculator implements MathCalculatorInterface
{
    private const DEFAULT_SCALE = 14;

    /**
     * @var int
     */
    private $scale;

    /**
     * BcmathCalclator constructor.
     *
     * @param int $scale
     */
    public function __construct(int $scale = self::DEFAULT_SCALE)
    {
        $this->scale = $scale;
    }

    public function divide(string $number1, string $number2): string
    {
        return bcdiv($number1, $number2, $this->scale);
    }

    public function multiply(string $number1, string $number2): string
    {
        return bcmul($number1, $number2, $this->scale);
    }

    public function ceil(string $number, int $precision): string
    {
        $resultNumber = rtrim($number, '0.');
        $hasMinus = strpos($resultNumber, '-') === 0;
        $decimalSeparatorPosition = strpos($resultNumber, '.');
        $fractionalPart = $decimalSeparatorPosition === false
            ? ''
            : substr($resultNumber, $decimalSeparatorPosition + 1);

        if (strlen($fractionalPart) > $precision) {
            $rightOperand = '0.' . str_repeat('0', $precision - 1) . '1';
            if ($hasMinus) {
                $rightOperand = '-' . $rightOperand;
            }

            return bcadd($resultNumber, $rightOperand, $precision);
        }

        return $resultNumber;
    }

    public function isZero(string $number): bool
    {
        $normalizedNumber = trim($number, '0.');

        return $normalizedNumber === '';
    }
}
