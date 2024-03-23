<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class EmailWithAttachment extends Mailable
{
    public $data;
    public $pdfFilePath;
    public $imageBase64;

    public function __construct($data, $pdfFilePath, $imageBase64)
    {
        $this->data = $data;
        $this->pdfFilePath = $pdfFilePath;
        $this->imageBase64 = $imageBase64;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Confirmacion de pedido')
            ->view('Dashboard.OrderSellers.Email')
            ->with('order', $this->data)
            ->with('logoname', $this->imageBase64)
            ->attach($this->pdfFilePath, [
                'as' => "{$this->data->id}-PEDIDO.pdf", // Nombre del archivo adjunto
                'mime' => 'application/pdf', // Tipo MIME del archivo
            ]);
    }
}
