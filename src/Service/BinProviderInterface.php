<?php

namespace App\Service;

interface BinProviderInterface {
    public function getBinData(string $bin): array;
}
