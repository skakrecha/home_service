<?php

namespace App\Jobs\SMS;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendCertificateExpirySMSJob implements ShouldQueue
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

        foreach($certificates as $key => $certificate){
            $property = $certificate->property;
            if($property->user)
            sendSMS('Your '.$property->description.' Address : '.$property->address.' inventory '.$certificate->name.' will expired on '.Carbon::parse($certificate->expiry_date)->format('M d, Y'), $property->user->phone_number);
        }
    }
}
