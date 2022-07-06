<?php

namespace App\Service;

use App\Entity\Contact;
use App\Entity\Devi;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendMail(string $mail, string $view, $params = [], string $subject = 'nouvel demande de contact depuis Atlantique Spa!'): void
    {
        /* datat instance de contact*/
        /*if ($data instanceof Contact){
            $from = $data->getEmail();
        }*/
        /*if ($data instanceof Devi){
            $from = $data->getEmail();
            $params = ['nom'=> $data->getNom(), 'prenom'=> $data->getPrenom()];
        }*/

        $email = (new TemplatedEmail())    /* les () permettent d'acceder aux methodes donc faire plusieurs appels */
            ->subject($subject)
            ->from($mail)
            ->to('test@localhost.fr')
            ->htmlTemplate($view)
            ->context($params);

        $this->mailer->send($email);

        /* */
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            throw new TransportException($exception);
        }
    }
}
