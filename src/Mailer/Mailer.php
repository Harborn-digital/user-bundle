<?php

declare(strict_types=1);

/*
 * This file is part of the user bundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\UserBundle\Mailer;

use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Mailer\Mailer as BaseMailer;
use Symfony\Component\Mime\Email;
use Twig\Environment;

/**
 * @codeCoverageIgnore WIP
 */
final class Mailer implements MailerInterface
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(private readonly BaseMailer $mailer, private readonly string $fromEmail, Environment $twig)
    {
        $this->twig      = $twig;
    }

    public function createMessageAndSend(string $name, $to, array $parameters = []): Email
    {
        return $this->doSend($to, $this->createHTMLBody($name, $parameters), $parameters);
    }

    private function createHTMLBody(string $name, array $parameters): string
    {
        $parameters['name'] ??= $name;

        $html = $this->twig->render(sprintf('@ConnecthollandUser/emails/%s.html.twig', $name), $parameters);

        return $html;
    }

    private function doSend($to, string $body, array $parameters): Email
    {
        $crawler = new Crawler($body);

        $message = (new Email())
            ->subject($crawler->filter('head > title')->text())
            ->from($this->fromEmail)
            ->to($to)
            ->text((new HtmlConverter())->convert($crawler->filter('body')->html()))
            ->html($crawler->html());

        if (isset($parameters['attachment'])) {
            $message->attach($parameters['attachment']);
        }

        $this->mailer->send($message);

        return $message;
    }
}
