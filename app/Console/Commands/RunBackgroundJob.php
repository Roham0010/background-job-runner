<?php

namespace App\Console\Commands;

use App\BackgroundJobRunnerService\Factories\JobFactory;
use App\BackgroundJobRunnerService\Logger\JobLogger;
use App\BackgroundJobRunnerService\Services\BackgroundJobRunner;
use Illuminate\Console\Command;

class RunBackgroundJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:run-background-job {className} {method} {parameters?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a background job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $className = $this->argument('className');
        $method = $this->argument('method');
        $parameters = json_decode($this->argument('parameters'), true) ?? [];

        $job = JobFactory::create($className, $method, $parameters);
        $logger = new JobLogger();

        $logger->setJobName($className);

        $job && BackgroundJobRunner::getInstance()->run($job, $method, $logger);
    }
}
