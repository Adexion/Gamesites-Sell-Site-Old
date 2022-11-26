<?php

namespace App\Controller\Client;

use App\Entity\Template;
use App\Kernel;
use App\Repository\ConfigurationRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AbstractRenderController extends AbstractController
{
    private const DEFAULT = 'client/guild/dark/';

    private ?Template $template;
    private string $env;

    public function __construct(ConfigurationRepository $configurationRepository, Kernel $kernel)
    {
        $this->template = $configurationRepository->findOneBy([])->getTemplate();
        $this->env = $kernel->getEnvironment();
    }

    /** @throws Exception */
    protected function renderTheme(string $view, array $parameters = [], Response $response = null): Response
    {
        if ( ! $this->template || ! $this->template->getId()) {
            return $this->render(self::DEFAULT . $view, $parameters, $response);
        }

        try {
            return $this->render($this->template->getPath() . $view, $parameters, $response);
        } catch (Exception $e) {
            if ('dev' === $this->env) {
                throw $e;
            }
        }

        return $this->redirectToRoute('index');
    }
}