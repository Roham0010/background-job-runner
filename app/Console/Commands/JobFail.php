<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class JobFail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:job-fail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        runBackgroundJob('Email', 'sendEmail', ['eemail' => "email@gmail.com", 'subject' => 'sbj', 'message' => 'msg']);
    }
}
