<?php

namespace App\Extension;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Component\HttpKernel\KernelInterface;

class FileExtension extends AbstractExtension
{
    private KernelInterface $appKernel;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('imageExist', [$this, 'remoteFileExists']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('imageExist', [$this, 'remoteFileExists'])
        ];
    }

    public function remoteFileExists(string $image): bool
    {
        $path = $this->appKernel->getProjectDir() . '/public/assets/images/';
        $content = @file_exists($path . $image);

        return $content !== false;
    }
}