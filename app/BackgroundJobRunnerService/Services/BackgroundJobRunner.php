<?php

namespace App\BackgroundJobRunnerService\Services;

use App\BackgroundJobRunnerService\Contracts\JobInterface;
use App\BackgroundJobRunnerService\Logger\JobLogger;
use App\BackgroundJobRunnerService\Logger\JobLoggerInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class BackgroundJobRunner
{
	private static $instance;

	private function __construct() {}

	public static function getInstance(): self
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function run(JobInterface $job, string $method, JobLoggerInterface $logger): void
	{
		try {
			// Log the start of the job
			$logger->setInfo('RUNNING <===============>')->log();

			$job->setLogger($logger);
			$job->execute($method);
		} catch (Exception $e) {
			$logger->setError("Job execution failed")->setAdditionalInfo(['error' => $e->getMessage()])->log();
		}
	}
}
