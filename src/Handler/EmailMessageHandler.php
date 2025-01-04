<?php
declare(strict_types=1);
namespace App\Handler;

use App\Message\EmailMessage;
use App\Service\EmailNotificatorService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EmailMessageHandler
{
    public function __construct(
        protected LoggerInterface $logger,
        private readonly EmailNotificatorService $notificator,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(EmailMessage $sampleMessage): void
    {
        $jsonData = json_decode($sampleMessage->getContent(), true);

        $fromEmail = $jsonData['fromEmail'] ?? '';
        $toEmail = $jsonData['toEmail'] ?? '';

        $this->notificator->sendNotificationEmail($fromEmail, $toEmail);

        $this->logger->info('Email from ' . $fromEmail . ' was sended to ' . $toEmail, ['app']);
    }
}
