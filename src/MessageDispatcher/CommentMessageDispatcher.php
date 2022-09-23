<?php

namespace App\MessageDispatcher;

use App\Entity\Comment;
use App\Message\CommentMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class CommentMessageDispatcher
{
    public function __construct(
        private MessageBusInterface $bus
    ) {}

    public function dispatchMessage(Request $request, Comment $comment)
    {
        $context = [
            'user_ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('user-agent'),
            'referrer' => $request->headers->get('referer'),
            'permalink' => $request->getUri(),
        ];

        $this->bus->dispatch((new CommentMessage($comment->getId(), $context)));
    }
}