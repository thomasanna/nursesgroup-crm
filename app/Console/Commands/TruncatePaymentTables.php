<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Timesheet;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\PaymentArchive;
use App\Models\InvoiceArchive;

class TruncatePaymentTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Timesheet::truncate();
        Payment::truncate();
        Invoice::truncate();
        PaymentArchive::truncate();
        InvoiceArchive::truncate();
    }
}
