<?php

namespace App\Config;

class ZohoConfig
{
    public function __construct(
        public string $ownerName,
        public string $appName,
        public string $reportName
    ) {}
}
