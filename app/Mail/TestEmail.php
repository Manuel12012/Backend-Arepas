<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TestEmail extends Mailable
{
    public function build()
    {
        return $this
            ->subject('Prueba de correo')
            ->html('
                <h2>Correo de prueba</h2>
                <p>Si recibes este correo, Resend está correctamente configurado.</p>
            ');
    }
}