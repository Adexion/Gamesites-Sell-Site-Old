<?php

namespace App\Controller\Client;

use App\Enum\TemplateEnum;
use App\Repository\ConfigurationRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\SyntaxError;
use UnexpectedValueException;

class AbstractRenderController extends AbstractController
{
    private TemplateEnum $template;

    public function __construct(ConfigurationRepository $configurationRepository)
    {
        try {
            $this->template = new TemplateEnum($configurationRepository->findOneBy([])->getTemplate());
        } catch (UnexpectedValueException $e) {
            $this->template = new TemplateEnum(TemplateEnum::NONE);
        }
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        if (!$this->template->getValue()) {
            return parent::render($view, $parameters, $response);
        }

        try {
            $replaced = str_replace('client', $this->template->getValue(), $view);

            return parent::render($replaced, $parameters, $response);
        } catch (SyntaxError $e) {
            if ($_ENV['APP_ENV'] === 'dev')  {
                throw $e;
            }
        }

        try {
            return parent::render($view, $parameters, $response);
        } catch (SyntaxError $e) {
            return $this->redirectToRoute('index');
        }
    }
}