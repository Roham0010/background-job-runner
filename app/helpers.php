<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('runBackgroundJob')) {
	function runBackgroundJob(string $jobType, string $method, array $parameters = []): void
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			pclose(popen('start /B php ' . base_path() . '/artisan job:run-background-job ' . $jobType . ' ' . $method . ' ' . escapeshellarg(json_encode($parameters)), 'r'));
		} else {
			exec('php ' . base_path() . '/artisan job:run-background-job ' . $jobType . ' ' . $method . ' ' . escapeshellarg(json_encode($parameters)) . ' > /dev/null &');
		}
	}
}

if (!function_exists('BGJobsLog')) {
	function BGJobsLog(string $type, string $message = '', ...$args): void
	{
		if (!in_array($type, ['error', 'info'])) {
			throw new Exception('No log type');
		}

		Log::channel('background_jobs')->{$type}($message, $args);
	}
}
