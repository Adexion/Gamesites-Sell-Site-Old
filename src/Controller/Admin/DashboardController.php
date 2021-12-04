<?php

namespace App\Controller\Admin;

use App\Entity\Additional;
use App\Entity\Configuration;
use App\Entity\GuildItem;
use App\Entity\Item;
use App\Entity\ItemHistory;
use App\Entity\Payment;
use App\Entity\Rank;
use App\Entity\Rule;
use App\Entity\Server;
use App\Entity\User;
use App\Entity\Voucher;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/admin/changes", name="changes")
     */
    public function changes(): Response
    {
        return $this->render('admin/changes.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SellSite');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-terminal');
        yield MenuItem::linkToRoute('Changelog', 'fas fa-forward', 'changes');

        yield MenuItem::section('Content');
        yield MenuItem::linkToCrud('Rule', 'fa fa-book', Rule::class);
        yield MenuItem::linkToCrud('Rank', 'fas fa-users', Rank::class);

        yield MenuItem::section('Item Shop');
        yield MenuItem::linkToCrud('Item', 'fas fa-shopping-cart', Item::class);
        yield MenuItem::linkToCrud('Payment', 'fas fa-money-bill', Payment::class);
        yield MenuItem::linkToCrud('Voucher', 'fas fa-receipt', Voucher::class);
        yield MenuItem::linkToCrud('History', 'fa fa-history', ItemHistory::class);

        yield MenuItem::section('Settings');
        yield MenuItem::linkToCrud('Server', 'fas fa-server', Server::class);
        yield MenuItem::linkToCrud('Guild Item', 'fas fa-sitemap', GuildItem::class);
        yield MenuItem::linkToCrud('Additional', 'fa fa-plus', Additional::class);
        yield MenuItem::linkToCrud('Configuration', 'fas fa-cog', Configuration::class);

        yield MenuItem::section('');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
    }
}
