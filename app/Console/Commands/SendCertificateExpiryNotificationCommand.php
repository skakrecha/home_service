<?php

namespace App\Console\Commands;

use App\Jobs\Notification\SendCertificateExpiryNotificationJob;
use App\PropertyCertificate;
use Illuminate\Console\Command;

class SendCertificateExpiryNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificate-expiry:notification';

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
     * @return int
     */
    public function handle()
    {
        $certificates = PropertyCertificate::whereDate('expiry_date', today())->get();

        SendCertificateExpiryNotificationJob::dispatch($certificates)->onQueue('notify');

    }
}
