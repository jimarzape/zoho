<?php

namespace App\Config\Zoho;

class TransactionConfig
{
    public function __construct(
        public string $ownerName,
        public string $appName,
        public string $reportName
    ) {}
}
