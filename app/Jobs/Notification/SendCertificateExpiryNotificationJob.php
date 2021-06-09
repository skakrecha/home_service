<?php

namespace App\Jobs\Notification;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\SendCertificateExpiry;
use Illuminate\Support\Facades\Notification;
use App\Jobs\SMS\SendCertificateExpirySMSJob;

class SendCertificateExpiryNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $certificates;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($certificates)
    {
        $this->certificates = $certificates;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $certificates = $this->certificates;

        SendCertificateExpirySMSJob::dispatch($certificates)->onQueue('notify');

        foreach ($certificates as $key => $certificate) {
            $property = $certificate->property;
            // SendCertificateExpiryFCMNotificationJob::dispatch($certificate)->onQueue('notify')->delay(now()->addSeconds($key + 1));
            Notification::send([$property->user], new SendCertificateExpiry($certificate));
        }
    }
}
