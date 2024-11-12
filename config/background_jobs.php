<?php

use App\BackgroundJobRunnerService\Jobs\Email\SendEmailJob;
use App\BackgroundJobRunnerService\Jobs\Invoice\CompleteInvoiceJob;

return [
	'retry_attempts' => 3,
	'retry_delay' => 5, // seconds
	'jobs' => [
		'Invoice' => [
			'job_class' => CompleteInvoiceJob::class,
			'retry_attempts' => 3,
			'retry_delay' => 10,
		],
		'Email' => [
			'job_class' => SendEmailJob::class,
			'retry_attempts' => 5,
			'retry_delay' => 5,
		],
	],
];
