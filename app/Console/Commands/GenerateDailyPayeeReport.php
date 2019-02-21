<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\GenerateDailyPayeeReportEvent;

class GenerateDailyPayeeReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Daily Payee Reports of Booking';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        event(new GenerateDailyPayeeReportEvent());
    }
}
