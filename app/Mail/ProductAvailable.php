<?php

namespace App\Mail;

use App\Models\Products;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductAvailable extends Mailable
{
    use Queueable, SerializesModels;

    protected $products;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Products $products)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('test@example.com', 'Example')
            ->view('emails.available')
            ->with([
                'productName' => $this->products->name
            ]);
    }
}
