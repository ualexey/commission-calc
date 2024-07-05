<?php

namespace App\Tests\Service;

use App\Service\CommissionCalculator;
use App\Service\CommissionProcessor;
use PHPUnit\Framework\TestCase;

class CommissionProcessorTest extends TestCase
{
    private CommissionCalculator $calculator;
    private CommissionProcessor $processor;

    protected function setUp(): void
    {
        $this->calculator = $this->createMock(CommissionCalculator::class);
        $this->processor = new CommissionProcessor($this->calculator);
    }

    public function testProcessFile(): void
    {
        $file = __DIR__ . '/test_input.txt';
        file_put_contents($file, json_encode(['bin' => '45717360', 'amount' => 100, 'currency' => 'EUR']) . "\n");

        $this->calculator
            ->method('calculateCommission')
            ->willReturn(1.0);

        $this->expectOutputString("1.00\n");
        $this->processor->processFile($file);

        unlink($file);
    }

    public function testProcessFileWithInvalidData(): void
    {
        $file = __DIR__ . '/test_input_invalid.txt';
        file_put_contents($file, "invalid json\n");

        $this->expectOutputString("");
        $this->processor->processFile($file);

        unlink($file);
    }
}
