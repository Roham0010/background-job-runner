<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class JobInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:job-invoice';

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
        runBackgroundJob('Invoice', 'completeInvoice', ['invoiceId' => "123"]);
    }
}
