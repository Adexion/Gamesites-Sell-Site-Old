<?php

namespace App\Controller\Admin;

use App\Entity\Link;
use App\Entity\Head;
use App\Entity\Bans;
use App\Entity\Item;
use App\Entity\Rank;
use App\Entity\Rule;
use App\Entity\User;
use App\Entity\Image;
use App\Enum\UrlEnum;
use App\Entity\Server;
use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\Payment;
use App\Entity\Voucher;
use App\Entity\GuildItem;
use App\Entity\Additional;
use App\Entity\ItemHistory;
use App\Entity\PaySafeCard;
use App\Entity\Configuration;
use App\Entity\Administration;
use App\Repository\ServerRepository;
use App\Service\Connection\QueryService;
use App\Controller\Admin\Action\Console;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\Action\Authentication;
use App\Controller\Admin\Action\PasswordManager;
use App\Service\Connection\ExecuteServiceFactory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    use PasswordManager;
    use Console;
    use Authentication;

    /**
     * @Route("/admin", name="admin")
     */
    public function index(ServerRepository $serverRepository = null, ExecuteServiceFactory $executeServiceFactory = null): Response
    {
        ini_set('default_socket_timeout', 1);
        $response = json_decode(file_get_contents(UrlEnum::GAMESITES_URL . 'v1/application/information/' . $_ENV['COUPON']), true);
        $server = $serverRepository->findOneBy(['isDefault' => true]);
        $service = $server ? $executeServiceFactory->getExecutionService($server) : null;

        return $this->render('admin/dashboard.html.twig', [
            'response' => $response,
            'serverInfo' => $service ? $service->getInfo() : [],
        ]);
    }

    /**
     * @Route("/admin/changes", name="changes")
     */
    public function changes(): Response
    {
        return $this->render('admin/changes.html.twig');
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('admin')
            ->addWebpackEncoreEntry('console');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->renderSidebarMinimized(false)
            ->renderContentMaximized(true)
            ->disableUrlSignatures()
            ->setTitle('GameSites');
    }

    public function configureMenuItems(): iterable
    {
        $response = json_decode(file_get_contents(UrlEnum::GAMESITES_URL . 'v1/application/information/' . $_ENV['COUPON']), true);

        if ($response['expireDate'] <= date('Y-m-d')) {
            return [];
        }

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
        yield MenuItem::linkToCrud('Image Repository', 'fas fa-image', Image::class);

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
        yield MenuItem::linkToCrud('Redirect Link', 'fas fa-link', Link::class);
        yield MenuItem::linkToCrud('Heading', 'fas fa-heading', Head::class);
        yield MenuItem::linkToCrud('Configuration', 'fas fa-cog', Configuration::class);

        yield MenuItem::section('Użytkownicy');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToRoute('2FA Google', 'fab fa-google', 'user2fa');
        yield MenuItem::linkToRoute('Menedżer haseł', 'fas fa-key', 'admin_password_manager');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setAvatarUrl('https://crafthead.net/cube/' . ($this->getUser()->getNick() ?: 'steve'))
            ->setName($this->getUser()->getNick() ?: $this->getUser()->getUserIdentifier());
    }
}
