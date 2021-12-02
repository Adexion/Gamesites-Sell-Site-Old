<?php

namespace App\Service\Mail;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SenderService
{
    private SchemaListProvider $provider;
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->provider = new SchemaListProvider();
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailBySchema(string $schemaId, $data): void
    {
        $schema = $this->provider->provide($schemaId);

        $body = str_replace($schema['replace'], $data, $schema['body']);
        $email = (new Email())
            ->from('szczupak13@gmail.com')
            ->to($data['email'])
            ->subject($schema['subject'])
            ->html($body);

        $this->mailer->send($email);
    }
}
