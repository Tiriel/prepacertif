<?php

namespace App;

use App\Checker\SpamChecker;
use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsMessageHandler]
class CommentMessageHandler
{
    public function __construct(
        private CommentRepository $commentRepository,
        private WorkflowInterface $commentStateMachine,
        private SpamChecker $spamChecker
    )
    {
    }

    public function __invoke(CommentMessage $message): void
    {
        $comment = $this->commentRepository->find($message->getId());
    }
}