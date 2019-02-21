<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\GenerateTimesheetConsoleEvent;

class GenerateTimesheetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timesheet:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will create new timesheets based on the bookings';

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
      event(new GenerateTimesheetConsoleEvent());
    }
}
