<?php

namespace App\Service\Connection;

use App\Entity\Server;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

class PluginService implements ConnectionInterface, ExecuteInterface, QueryInterface, ConsoleInterface
{
    private Server $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    function getConnection(): HttpClientInterface
    {
        return HttpClient::create();
    }

    /** @throws TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface */
    public function execute($command, string $username = '', int $amount = 1): ?string
    {
        $options = [
            'headers' => [
                'x-api-key' => $this->server->getConPassword(),
                'content-type' => 'application/json'
            ],
            'body' => json_encode([
                'nickName' => $username,
                'command' => str_replace(['%player%', '%amount%'], [$username, $amount], '/' . $command),
            ])
        ];

        return $this->getConnection()
            ->request('POST', $this->getUrl('command'), $options)->getContent(false);
    }

    /** @throws TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface */
    public function getPlayerList(): ?array
    {
        return json_decode($this->getConnection()
            ->request('GET', $this->getUrl('info'), [])->getContent(), true)['serverInfoPlayer']['players'];
    }

    /** @throws TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface */
    public function getInfo(): ?array
    {
        $response = json_decode($this->getConnection()
            ->request('GET', $this->getUrl('info'), [])->getContent(), true)['serverInfoPlayer'];

        return [
            'Players' => $response['onlinePlayers'],
            'MaxPlayers' => $response['maxPlayers'],
        ];
    }

    /** @throws TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface */
    public function getConsole(): ?array
    {
        $options = [
            'headers' => [
                'x-api-key' => $this->server->getConPassword(),
                'content-type' => 'application/json'
            ]
        ];

        return json_decode($this->getConnection()
            ->request('POST', $this->getUrl('console'), $options)->getContent(), true);
    }

    public function isPlayerLoggedIn(string $username): bool
    {
        return true;
    }

    private function getUrl(string $method): string
    {
       return 'http://' . $this->server->getConIp() . ':' . $this->server->getConPort() . '/' . $method;
    }
}