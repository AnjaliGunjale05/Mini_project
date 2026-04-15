<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;


class ContactMail extends Mailable
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('New Contact Message')
            ->view('email.contact')
            ->with('data', $this->data);
    }
}
