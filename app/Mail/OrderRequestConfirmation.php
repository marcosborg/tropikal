<?php

namespace App\Mail;

use App\Models\OrderRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;

class OrderRequestConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public OrderRequest $orderRequest, public bool $internal = false) {}
    public function envelope(): Envelope { return new Envelope(subject: ($this->internal ? 'Novo pedido ' : 'Recebemos o seu pedido ').$this->orderRequest->reference); }
    public function content(): Content { return new Content(view: 'emails.order-request'); }
}
