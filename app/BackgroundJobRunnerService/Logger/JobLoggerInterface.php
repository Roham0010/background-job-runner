<?php

namespace App\BackgroundJobRunnerService\Logger;

interface JobLoggerInterface
{
	// protected $jobName;
	// protected $status;
	// protected $logType = 'info';
	// protected $additionalInfo = [];

	public function setJobName(string $jobName);
	public function setOutputType(string $jobName);

	public function setStatus(string $status);

	public function setInfo(string $message = null);

	public function setError(string $message = null);

	public function setAdditionalInfo(array $info);

	public function log();
}
