<?php

namespace App\BackgroundJobRunnerService\Jobs\Invoice;

use App\BackgroundJobRunnerService\Abstracts\JobAbstract;
use Exception;
use Illuminate\Support\Facades\Log;

class CompleteInvoiceJob extends JobAbstract
{
	const TYPE = 'Invoice';
	protected $invoiceId;

	public function __construct(int $invoiceId)
	{
		parent::__construct();
		$this->invoiceId = $invoiceId;
	}

	protected function completeInvoice(): void
	{
		try {
			$this->logger->setInfo("FROM_JOB_CLASS: {starting invoice completion}")->log();

			// Simulate completing invoice
			$r = rand(1, 10);
			if ($r % 2 == 0) {
				throw new Exception('Sample ERROR');
			}

			$this->logger->setInfo("FROM_JOB_CLASS: {invoice completed: $this->invoiceId}")->log();
		} catch (Exception $e) {
			$this->logger->setError($e->getMessage())->log();
			throw $e;
		}
	}
}
