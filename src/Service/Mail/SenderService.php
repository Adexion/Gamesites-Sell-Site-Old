<?php

namespace App\Service\Mail;

use App\Entity\Configuration;
use App\Repository\ConfigurationRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SenderService
{
    private SchemaListProvider $provider;
    private MailerInterface $mailer;
    private ?Configuration $configuration;

    public function __construct(MailerInterface $mailer, ConfigurationRepository $repository)
    {
        $this->mailer = $mailer;
        $this->provider = new SchemaListProvider();
        $this->configuration = $repository->findOneBy([]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailBySchema(string $schemaId, $data): void
    {
        $schema = $this->provider->provide($schemaId);

        $body = str_replace($schema['replace'], $data, $schema['body']);
        $email = (new Email())
            ->from()
            ->to($this->configuration->getEmail())
            ->subject($schema['subject'])
            ->html($body);

        $this->mailer->send($email);
    }
}
