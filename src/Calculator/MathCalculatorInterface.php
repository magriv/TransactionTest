<?php

namespace App\Calculator;

interface MathCalculatorInterface
{
    public function divide(string $number1, string $number2): string;

    public function multiply(string $number1, string $number2): string;

    public function ceil(string $number, int $precision): string;

    public function isZero(string $number): bool;
}
