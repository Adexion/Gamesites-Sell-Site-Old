<?php

namespace App\Controller\Client;

use App\Repository\RuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(requirements={"_locale": "en|pl"}, path="{_locale}") */
class RuleController extends AbstractController
{
    /** @Route(name="rule", path="/rule") */
    public function rule(RuleRepository $repository): Response
    {
        $rule = $repository->findOneBy([]);

        return $this->render('client/rule.html.twig', [
            'rule' => $rule ? $rule->getHtml() : ''
        ]);
    }
}