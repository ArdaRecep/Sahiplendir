<?php

namespace App\Mail;

use App\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ListingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Listing $listing;

    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
    }

    public function build()
    {
        return $this
            ->subject('İlanınız Onaylandı')
            ->markdown('emails.listings.approved', [
                'listing' => $this->listing,
            ]);
    }
}
