<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransmittalDelivery extends Mailable
{
    use SerializesModels;

    public $transmittals;
    // public $deliveries;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transmittals)
    {
        $this->transmittals = $transmittals;
        // $this->deliveries = $deliveries;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            // ->from(Auth::user()->email, Auth::user()->full_name) // send from user who logged in
            ->from('notification@it.arka.co.id', 'Notification IT')
            ->subject('#' . $this->transmittals->receipt_full_no . ' Transmittal Form Delivery')
            ->markdown('emails.transmittal_delivery')
            ->with([
                'transmittals' => $this->transmittals,
                // 'deliveries' => $this->deliveries,
                'link' => url('transmittals/' . $this->transmittals->id),
            ]);
    }
}
