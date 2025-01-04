<?php
declare(strict_types=1);
namespace App\Handler;

use App\Message\EmailMessage;
use App\Message\UserFriendRequestMessage;
use App\Service\EmailNotificatorService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserFriendRequestMessageHandler
{
    public function __construct(
        protected LoggerInterface $logger,
        private readonly EmailNotificatorService $notificator,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(UserFriendRequestMessage $userFriendRequestMessage): void
    {
        $jsonData = json_decode($userFriendRequestMessage->getContent(), true);

        $fromEmail = $jsonData['user']['email'];
        $toEmail = $jsonData['toFriend']['email'];

        $this->notificator->sendUserFriendRequestEmail($jsonData);

        $this->logger->info('User friend request email from ' . $fromEmail . ' was sended to ' . $toEmail, ['app']);
    }
}
