<?php

namespace App\BackgroundJobRunnerService\Abstracts;

use Illuminate\Support\Facades\Log;
use App\BackgroundJobRunnerService\Contracts\JobInterface;
use App\BackgroundJobRunnerService\Logger\JobLogger;
use App\BackgroundJobRunnerService\Logger\JobLoggerInterface;
use Exception;

abstract class JobAbstract implements JobInterface
{
	protected const TYPE = '';
	protected int $retryAttempts;
	protected int $retryDelay;
	protected JobLoggerInterface $logger;

	public function __construct()
	{
		$jobTypeAttributes = config('background_jobs.jobs.' . static::TYPE);

		$this->retryAttempts = $jobTypeAttributes['retry_attempts'] ??
			config('background_jobs.retry_attempts', 3);
		$this->retryDelay = $jobTypeAttributes['retry_delay'] ??
			config('background_jobs.retry_delay', 5);
	}

	public function setLogger(JobLoggerInterface $logger): void
	{
		$this->logger = $logger;
	}

	public function execute(string $method): void
	{
		$attempt = 0;
		while ($attempt < $this->retryAttempts) {
			$this->logger->setInfo("ATTEMPT: $attempt")->log();
			try {
				$this->{$method}();
				$this->logger->setInfo('SUCCEED <===============>')->log();
				return;
			} catch (Exception $e) {
				$attempt++;
				$this->logger->setInfo('FAILED attempt #' . $attempt)->log();
				$this->logger->setError($e->getMessage())->log();
				if ($attempt < $this->retryAttempts) {
					sleep($this->retryDelay);
				} else {
					$this->logger->setInfo('FAILED <===============>')->log();
				}
			}
		}
	}
}
