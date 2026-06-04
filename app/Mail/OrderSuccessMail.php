<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $name;


    public function __construct($inFoOrder, $name)
    {
        //
        $this->order =  $inFoOrder;
        $this->name = $name;
    }

    /**
     * Get the message envelope.
     */


    public function build()
    {
        return $this->subject('Đơn hàng của bạn đã được đặt thành công!')
            ->view('email.order_success')
            ->with([
                'order' => $this->order,

                'name' => $this->name
            ]);
    }
}
