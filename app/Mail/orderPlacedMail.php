<?php

namespace App\Mail;
use App\Models\Order;
use Illuminate\Mail\Mailable;

class orderPlacedMail extends Mailable
{
    public $order;
    
    public function __construct(Order $order)
    {
        $this->order=$order;
    }

    public function build(){
        return $this->subject('order confirmation')->view('email.order_confirmation');
    }   
}
