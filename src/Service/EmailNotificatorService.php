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
    private function sendEmail(array $emailParams): void
    {
        $email = (new Email())
            ->from($emailParams['from'])
            ->sender($emailParams['from'])
            ->to($emailParams['to'])

            ->priority(Email::PRIORITY_HIGH)
            ->subject($emailParams['subject'])
            ->html($emailParams['content']);

        $this->mailer->send($email);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendUserFriendRequestEmail(array $userRequestData): void
    {
        $url = $userRequestData['acceptFriendUrl'];
        $fromEmail = $userRequestData['user']['email'];
        $toEmail = $userRequestData['toFriend']['email'];
        $host = self::URL;

        $html = <<<html
        <!DOCTYPE html>
        <html>
            <head>
            </head>
            <body>
                <center><h2>You have new frient request from $fromEmail</h2></center><br>
                <center>
                    <p>
                        You can accept this request for adding new friend by click this url <a href="$url">$url</a>
                    </p>
                </center>
                <br>
                <p style="text-align: center;">Visit us at: <a href="$host">$host</a></p>
            </body>
        </html>
        html;

        $this->sendEmail([
            'content' => $html,
            'subject' => 'Friend Request',
            'from' => $fromEmail,
            'to' => $toEmail,
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendNotificationEmail(string $fromEmail, string $toEmail): void
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

        $this->sendEmail([
            'content' => $html,
            'subject' => 'OK',
            'from' => $fromEmail,
            'to' => $toEmail,
        ]);
    }
}