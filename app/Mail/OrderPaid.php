<?php
namespace App\Mail;
use App\Models\Order; use Illuminate\Bus\Queueable; use Illuminate\Mail\Mailable; use Illuminate\Mail\Mailables\{Content,Envelope}; use Illuminate\Queue\SerializesModels;
class OrderPaid extends Mailable {use Queueable,SerializesModels;public function __construct(public Order $order){}public function envelope():Envelope{return new Envelope(subject:'Encomenda '.$this->order->number.' confirmada');}public function content():Content{return new Content(view:'emails.order-paid');}}
