<?php

namespace App\BackgroundJobRunnerService\Jobs\Email;

use App\BackgroundJobRunnerService\Abstracts\JobAbstract;
use Exception;
use Illuminate\Support\Facades\Log;

class SendEmailJob extends JobAbstract
{
	protected const TYPE = 'Email';
	protected $email;
	protected $subject;
	protected $message;

	public function __construct(string $email, string $subject, string $message)
	{
		parent::__construct();
		$this->email = $email;
		$this->subject = $subject;
		$this->message = $message;
	}

	protected function sendEmail(): void
	{
		try {
			$logMessage = "FROM_JOB_CLASS: {Sending email to: $this->email, Subject: $this->subject}";
			$this->logger->setInfo($logMessage)->log();

			// Simulate sending an email
			$r = rand(1, 10);
			if ($r % 2 == 0) {
				throw new Exception('Sample ERROR');
			}

			$this->logger->setInfo("FROM_JOB_CLASS: {Email sent successfully to: $this->email}")
				->log();
		} catch (Exception $e) {
			$this->logger->setError($e->getMessage());
			throw $e;
		}
	}
}
