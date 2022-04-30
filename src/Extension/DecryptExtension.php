<?php

namespace App\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class DecryptExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('mcrypt_encrypt', [$this, 'mcryptEncrypt']),
        ];
    }

    public function mcryptEncrypt(string $key, string $text): string
    {
        list($encrypted_data, $iv) = explode('::', base64_decode($text), 2);

        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    }
}