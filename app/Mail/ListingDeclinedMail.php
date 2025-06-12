<?php

namespace App\Mail;

use App\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ListingDeclinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Listing $listing;
    public string  $reason;

    public function __construct(Listing $listing, string $reason)
    {
        $this->listing = $listing;
        $this->reason  = $reason;
    }

    public function build()
    {
        return $this
            ->subject('İlanınız Reddedildi')
            ->markdown('emails.listings.declined', [
                'listing' => $this->listing,
                'reason'  => $this->reason,
            ]);
    }
}
