<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailNotificatorService
{
    private const URL = 'https://localhost/';

    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $fromEmail, string $toEmail): void
    {
        $host = self::URL;

        $html = <<<html
            <!DOCTYPE html>
            <html>
                <head>
                </head>
                <body>
                    <center><h2>You have new</h2></center><br>
                    <center><h2>OK</h2></center><br>
                    <p style="text-align: center;">sended by <a href="mailto:$fromEmail">$fromEmail</a></p>
                    <p style="text-align: center;">Visit us at: <a href="$host">$host</a></p>
                </body>
            </html>
            html;

        $email = (new Email())
            ->from($fromEmail)
            ->sender($fromEmail)
            ->to($toEmail) // $toEmail //'a.o.ermolenko@gmail.com'

            ->priority(Email::PRIORITY_HIGH)
            ->subject('OK')
            ->html($html);

        $this->mailer->send($email);
    }
}