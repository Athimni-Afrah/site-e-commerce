<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer{
    /**
     * @param MailerInterface $mailer
     */
    private $mailer;
    public function  __construct(MailerInterface  $mailer){
        $this->mailer= $mailer;

    }
    public function sendEmail($email, $token)
    {
        $email = (new TemplatedEmail())
            ->from('a.athamnia@smart-it-partner.com')
            ->to(new Address($email))
            ->subject('Thanks for signing up!')

            // path of the Twig template to render
            ->htmlTemplate('send_email/index.html.twig')

            // pass variables (name => value) to the template
            ->context([
               'token' => $token,
            ])
        ;

        $this->mailer->send($email);

    }
}

