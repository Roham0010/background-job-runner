<?php

namespace App\BackgroundJobRunnerService\Logger;

use Illuminate\Support\Facades\Log;

class JobLogger implements JobLoggerInterface
{
	protected string $jobName;
	protected string $status;
	protected string $logType = 'info';
	protected array $additionalInfo = [];
	protected string $outputType = 'string'; // can be also "json"

	public function setJobName(string $jobName)
	{
		$this->jobName = $jobName;
		return $this;
	}

	public function setOutputType(string $jobName)
	{
		$this->jobName = $jobName;
		return $this;
	}

	public function setStatus(string $status)
	{
		$this->status = $status;
		return $this;
	}

	public function setInfo(string $message = null)
	{
		$this->logType = 'info';
		$message && $this->setStatus($message);
		return $this;
	}

	public function setError(string $message = null)
	{
		$this->logType = 'error';
		$message && $this->setStatus($message);
		return $this;
	}

	public function setAdditionalInfo(array $info)
	{
		$this->additionalInfo = $info;
		return $this;
	}

	public function log()
	{
		if ($this->outputType == 'json') {
			$this->jsonLog();
		} else {
			$this->stringLog();
		}
	}

	private function clear()
	{
		$this->status = '';
		$this->logType = 'info';
		$this->additionalInfo = [];
	}

	public function stringLog()
	{

		$message = $this->jobName . ' -> ' . $this->status;

		Log::channel('background_jobs')->{$this->logType}($message, $this->additionalInfo);
		$this->clear();
	}


	public function jsonLog()
	{

		$logMessage = [
			'job_name' => $this->jobName,
			'status' => $this->status,
			'log_type' => $this->logType,
			'additional_info' => $this->additionalInfo,
			'timestamp' => now()->toDateTimeString(),
		];

		Log::channel('background_jobs')->{$this->logType}($logMessage);
		$this->clear();
	}
}
