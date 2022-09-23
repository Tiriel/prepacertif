<?php

namespace App\MessageHandler;

use App\Checker\SpamChecker;
use App\Entity\Comment;
use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsMessageHandler]
class CommentMessageHandler
{
    public function __construct(
        private CommentRepository   $commentRepository,
        private WorkflowInterface   $commentsStateMachine,
        private MessageBusInterface $bus,
        private MailerInterface     $mailer,
        private SpamChecker         $spamChecker,
        private string              $adminEmail
    ) {}

    public function __invoke(CommentMessage $message): void
    {
        $comment = $this->commentRepository->find($message->getId());
        if (!$comment) {
            return;
        }

        switch (true) {
            case $this->commentsStateMachine->can($comment, 'accept'):
                $this->applyTransitionFromScore($message, $comment);
                $this->bus->dispatch($message);
                break;
            case $this->commentsStateMachine->can($comment, 'publish'):
            case $this->commentsStateMachine->can($comment, 'publish_ham'):
                $this->sendNotificationEmail($comment);
                break;
        }
    }

    private function applyTransitionFromScore(CommentMessage $message, Comment $comment): void
    {
        $score = $this->spamChecker->checkSpam($comment, $message->getContext());
        $transition = match ($score) {
            SpamChecker::PROBABLE_SPAM => 'might_be_spam',
            SpamChecker::SPAM => 'reject_spam',
            default => 'accept'
        };
        $this->commentsStateMachine->apply($comment, $transition);
        $this->commentRepository->add($comment, true);
    }

    private function sendNotificationEmail(Comment $comment): void
    {
        $this->mailer->send((new NotificationEmail())
            ->subject('New comment posted.')
            ->htmlTemplate('emails/comment_notification.html.twig')
            ->from($this->adminEmail)
            ->to($this->adminEmail)
            ->context(['comment' => $comment])
        );
    }
}