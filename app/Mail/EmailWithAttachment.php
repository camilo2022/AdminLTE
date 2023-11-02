<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class EmailWithAttachment extends Mailable
{
    public $data;
    public $pdfFilePath;

    public function __construct($data, $pdfFilePath)
    {
        $this->data = $data;
        $this->pdfFilePath = $pdfFilePath;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Correo con PDF Adjunto')
            ->view('emails.email')
            ->attach($this->pdfFilePath, [
                'as' => 'example.pdf', // Nombre del archivo adjunto
                'mime' => 'application/pdf', // Tipo MIME del archivo
            ]);
    }
}
