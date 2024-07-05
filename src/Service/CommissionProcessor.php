<?php

namespace App\Service;

class CommissionProcessor
{
    private CommissionCalculator $calculator;

    public function __construct(CommissionCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function processFile(string $file): void
    {
        $rows = explode("\n", file_get_contents($file));
        foreach ($rows as $row) {
            if (empty($row)) {
                continue;
            }

            $data = json_decode($row, true);
            if (!$data) {
                continue;
            }
            $bin = $data['bin'];
            $amount = $data['amount'];
            $currency = $data['currency'];
            try {
                $commission = $this->calculator->calculateCommission($bin, $amount, $currency);
                $formattedCommission = number_format($commission, 2, '.', '');
                echo $formattedCommission . "\n";
            } catch (Exception $e) {
                echo('Exception: ' . $e->getMessage());
            }
        }
    }
}
