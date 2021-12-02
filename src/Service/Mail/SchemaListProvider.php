<?php

namespace App\Service\Mail;

use RuntimeException;

class SchemaListProvider
{
    private array $schemaList = [
        'passwordReset' => [
            'replace' => '%token%',
            'body' => '<p>Link do resetu hasła: <a href="http://mngame.pl/reset/%token%">https://mngame.pl/reset/%token%</a></p>',
            'subject' => 'Resetowanie hasła MNGame.pl'
        ],
        'contact' => [
            'replace' => ['%desc%', '%name%', '%email%'],
            'body' => '%name%: %email%<br/>%desc%',
            'subject' => 'Prośba o kontakt'
        ]
    ];

    public function provide(string $schemaId): array
    {
        if (!isset($this->schemaList[$schemaId])) {
            throw new RuntimeException('Email schema not set.');
        }

        return $this->schemaList[$schemaId];
    }
}
