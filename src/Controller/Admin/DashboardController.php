<?php

namespace App\Controller\Admin;

use App\Entity\Additional;
use App\Entity\Administration;
use App\Entity\Article;
use App\Entity\Bans;
use App\Entity\Configuration;
use App\Entity\Contact;
use App\Entity\GuildItem;
use App\Entity\Item;
use App\Entity\ItemHistory;
use App\Entity\Payment;
use App\Entity\PaySafeCard;
use App\Entity\PaySafeCardVoucher;
use App\Entity\Rank;
use App\Entity\Rule;
use App\Entity\Server;
use App\Entity\User;
use App\Entity\Voucher;
use App\Form\RConType;
use App\Service\QueryService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\QrCode\QrCodeGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use xPaw\SourceQuery\Exception\InvalidPacketException;
use xPaw\SourceQuery\Exception\SocketException;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route("/admin/console", name="console")
     */
    public function console(Request $request, QueryService $service): Response
    {
        $form = $this->createForm(RConType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Server $server */
            $server = $form->getData()['server'];
            try {
                $response = $service->execute($form->getData()['command'], $server);
            } catch (SocketException $e) {
                $response = $e->getMessage();
            }
        }

        return $this->render('admin/console.html.twig', [
            'form' => $form->createView(),
            'response' => $response ?? ''
        ]);
    }

    /**
     * @Route("/admin/changes", name="changes")
     */
    public function changes(): Response
    {
        return $this->render('admin/changes.html.twig');
    }

    /**
     * @Route("/admin/user/2fa", name="user2fa")
     */
    public function twoFactoryAuthentication(Request $request, GoogleAuthenticatorInterface  $googleAuthenticator, QrCodeGenerator $codeGenerator): Response
    {
        if ($request->request->get('generate')) {
            /** @var User $user */
            $user = $this->getUser();

            $secret = $googleAuthenticator->generateSecret();
            $user->setGoogleAuthenticatorSecret($secret);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $qrCodeContent = $codeGenerator->getGoogleAuthenticatorQrCode($user)->writeString();
        }

        if ($request->request->get('turnOff')) {
            /** @var User $user */
            $user = $this->getUser();
            $user->setGoogleAuthenticatorSecret(null);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('admin/2fa.html.twig', [
            'secret' => $secret ?? null,
            'qrCodeContent' => base64_encode($qrCodeContent ?? '')
        ]);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('ea-app')
            ->addWebpackEncoreEntry('console');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->renderSidebarMinimized()
            ->renderContentMaximized()
            ->disableUrlSignatures()
            ->setTitle('Panel Administracyjny');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Main');
        yield MenuItem::linkToRoute('Console', 'fa fa-terminal', 'console');
        yield MenuItem::linkToRoute('Changelog', 'fab fa-readme', 'changes');
        yield MenuItem::linkToCrud('Contact', 'fas fa-address-book', Contact::class);

        yield MenuItem::section('Content');
        yield MenuItem::linkToCrud('Rule', 'fa fa-book', Rule::class);
        yield MenuItem::linkToCrud('Artykuły', 'fa fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Rank', 'fas fa-users', Rank::class);
        yield MenuItem::linkToCrud('Bans', 'fas fa-ban', Bans::class);
        yield MenuItem::linkToCrud('Administration', 'fas fa-user-shield', Administration::class);

        yield MenuItem::section('Item Shop');
        yield MenuItem::linkToCrud('Item', 'fas fa-shopping-cart', Item::class);
        yield MenuItem::linkToCrud('Payment', 'fas fa-money-bill', Payment::class);
        yield MenuItem::linkToCrud('Voucher', 'fas fa-receipt', Voucher::class);
        yield MenuItem::linkToCrud('PaySafeCard', 'fas fa-lock', PaySafeCard::class);
        yield MenuItem::linkToCrud('History', 'fa fa-history', ItemHistory::class);

        yield MenuItem::section('Settings');
        yield MenuItem::linkToCrud('Server', 'fas fa-server', Server::class);
        yield MenuItem::linkToCrud('Guild Item', 'fas fa-sitemap', GuildItem::class);
        yield MenuItem::linkToCrud('Additional', 'fa fa-plus', Additional::class);
        yield MenuItem::linkToCrud('Configuration', 'fas fa-cog', Configuration::class);

        yield MenuItem::section('');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToRoute('2FA Google Authenticator', 'fab fa-google', 'user2fa');
    }
}
