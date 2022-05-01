<?php

namespace App\Controller\Client;

use App\Enum\TemplateEnum;
use App\Repository\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\SyntaxError;
use UnexpectedValueException;

class AbstractRenderController extends AbstractController
{
    private const DEFAULT = TemplateEnum::GUILD_DARK;
    private TemplateEnum $template;

    public function __construct(ConfigurationRepository $configurationRepository)
    {
        try {
            $this->template = new TemplateEnum($configurationRepository->findOneBy([])->getTemplate());
        } catch (UnexpectedValueException $e) {
            $this->template = new TemplateEnum(TemplateEnum::NONE);
        }
    }

    protected function renderTheme(string $view, array $parameters = [], Response $response = null): Response
    {
        if (!$this->template->getValue()) {
            return $this->render(self::DEFAULT . $view, $parameters, $response);
        }

        try {
            return $this->render($this->template->getValue() . $view, $parameters, $response);
        } catch (SyntaxError $e) {
            if ($_ENV['APP_ENV'] === 'dev') {
                throw $e;
            }
        }

        return $this->redirectToRoute('index');
    }
}