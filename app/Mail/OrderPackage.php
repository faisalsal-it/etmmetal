<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPackage extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $subject;
    public $fields;
    public $package;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($from, $subject, $fields, $package)
    {
        $this->sender = $from;
        $this->subject = $subject;
        $this->fields = $fields;
        $this->package = $package;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->sender)
        ->subject($this->subject)
        ->view('mail.package');
    }
}
