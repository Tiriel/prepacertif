<?php

namespace App\Checker;

use App\Entity\Comment;

class SpamChecker
{
    public const HAM = 0;
    public const PROBABLE_SPAM = 1;
    public const SPAM = 2;

    public function checkSpam(Comment $comment): int
    {
        return self::HAM;
    }
}