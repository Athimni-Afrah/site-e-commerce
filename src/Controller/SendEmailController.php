<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendEmailController extends AbstractController
{
    /**
     * @Route("/send/email", name="app_send_email")
     */

    public function index(): Response
    {

        return $this->render('send_email/index.html.twig', [
            'controller_name' => 'SendEmailController',
        ]);
    }
    /**
     * @Route("/sendmail", name="sendmail")
     */
    public function testMail(\Swift_Mailer $mailer)

    {
        $name = "Global Knowledge";
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('a.athamnia@smart-it-partner.com')
        ->setTo('athimni.afrah@gmail.com')
        ->setBody(
        $this->renderView(
            // templates/emails/registration.html.twig
        'send_email/index.html.twig',
        ['name' => $name]
        ),
        'text/html'
        )
            // you can remove the following code if you don't define a text version for your emails
            /*->addPart
            $this->renderView(
            // templates/emails/registration.txt.twig
            'emails/registration.txt.twig',
            ['name' => $name]
            ),
            'text/plain'
            )*/
        ;
        $mailer->send($message);
        return new Response("Bravo! mail sent");
        }
}
