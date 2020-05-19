<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailSchedule extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct()
    {
    }

    public function build()
    {
        return $this->from('contato.domiciliar@roseannedore.com.br', 'Contato - Lab. Roseanne Dore Soares')
                    ->subject('Solicitação de agendamento - Lab. Roseanne Dore Soares')
                    ->view('collect.public.sent');
    }
}
