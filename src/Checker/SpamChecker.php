<?php

namespace App\Checker;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpamChecker
{
    public const HAM = 0;
    public const PROBABLE_SPAM = 1;
    public const SPAM = 2;

    private string $endpoint;

    public function __construct(private HttpClientInterface $client, private string $site, string $akismetKey)
    {
        $this->endpoint = sprintf('https://%s.rest.akismet.com/1.1/comment-check', $akismetKey);
    }

    public function checkSpam(Comment $comment, array $context): int
    {
        $response = $this->client->request(Request::METHOD_POST, $this->endpoint, [
            'body' => array_merge($context, [
                'blog' => $this->site,
                'comment_type' => 'comment',
                'comment_author' => $comment->getCreatedBy()->getEmail(),
                'comment_author_email' => $comment->getEmail(),
                'comment_content' => $comment->getContent(),
                'comment_date_gmt' => $comment->getCreatedAt()->format('c'),
                'blog_lang' => 'en',
                'blog_charset' => 'URF-8',
                'is_test' => true,
            ]),
        ]);

        $headers = $response->getHeaders();
        if ('discard' === ($headers['x-akismet-pro-tip'][0] ?? '')) {
            return self::SPAM;
        }

        $content = $response->getContent();
        if (isset($headers['x-akismet-debug-help'][0])) {
            throw new \RuntimeException(sprintf('Unable to check for spam: %s (%s).', $content, $headers['x-akismet-debug-help'][0]));
        }

        return 'true' === $content ? self::PROBABLE_SPAM : self::HAM;
    }
}