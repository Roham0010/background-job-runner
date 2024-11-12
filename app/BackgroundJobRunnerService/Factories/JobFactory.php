<?php

namespace App\BackgroundJobRunnerService\Factories;

use Exception;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionParameter;

class JobFactory
{
	private static function validateParameters(string $class, array $parameters): void
	{
		$reflection = new ReflectionClass($class);
		$constructor = $reflection->getConstructor();
		if ($constructor) {
			$expectedParameters = $constructor->getParameters();
			$expectedKeys = array_map(fn(ReflectionParameter $param) => $param->getName(), $expectedParameters);

			// Check if all expected parameters are present
			foreach ($expectedKeys as $key) {
				if (!array_key_exists($key, $parameters)) {
					throw new InvalidArgumentException("$class Job ERROR Missing parameter: $key");
				}
			}

			// Check if any unexpected parameters are present
			foreach ($parameters as $key => $value) {
				if (!in_array($key, $expectedKeys, true)) {
					throw new InvalidArgumentException("$class Job ERROR Unexpected parameter: $key");
				}
			}
		}
	}

	public static function create(string $jobType, string $method, array $parameters): object|null
	{
		try {
			$jobMappings = config('background_jobs.jobs');
			if (!isset($jobMappings[$jobType])) {
				throw new Exception("$jobType Job ERROR Unknown job type: $jobType");
			}

			$jobClassName = $jobMappings[$jobType]['job_class'];

			if (!method_exists($jobClassName, $method)) {
				throw new Exception("$jobType Job ERROR Unknown method for class: $jobClassName");
			}

			self::validateParameters($jobClassName, $parameters);
		} catch (Exception $e) {
			BGJobsLog('error', $e->getMessage());
			return null;
		}
		return new $jobClassName(...$parameters);
	}
}
