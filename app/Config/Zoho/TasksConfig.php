<?php

namespace App\Config\Zoho;

class TasksConfig
{
    public function __construct(
        public string $portalId,
        public string $projectId,
    ) {}
}
