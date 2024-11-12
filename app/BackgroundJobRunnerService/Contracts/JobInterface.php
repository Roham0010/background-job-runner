<?php

namespace App\BackgroundJobRunnerService\Contracts;

use App\BackgroundJobRunnerService\Logger\JobLoggerInterface;

interface JobInterface
{
    public function execute(string $method): void;
    public function setLogger(JobLoggerInterface $logger): void;
}
