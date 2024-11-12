<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class JobSuccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:job-success';

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
        runBackgroundJob('Email', 'sendEmail', ['email' => "email@gmail.com", 'subject' => 'sbj', 'message' => 'msg']);
    }
}
