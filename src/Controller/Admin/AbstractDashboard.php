<?php

namespace App\Controller\Admin;

use App\Entity\Additional;
use App\Entity\Configuration;
use App\Entity\Contact;
use App\Entity\Customer\Module;
use App\Entity\Customer\Template;
use App\Entity\Head;
use App\Entity\Image;
use App\Entity\Rule;
use App\Entity\User;
use App\Enum\UrlEnum;
use App\Repository\ConfigurationRepository;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractDashboard extends AbstractDashboardController
{
    protected array $serverResponse;
    private Configuration $configuration;
    private TemplateRepository $templateRepository;

    public function __construct(ConfigurationRepository $configurationRepository, EntityManagerInterface  $configurationEntityManager)
    {
        ini_set('default_socket_timeout', 1);
        $this->serverResponse = json_decode(@file_get_contents(UrlEnum::GAMESITES_URL . 'v1/application/information/' . $_ENV['COUPON']), true) ?: [];

        $this->configuration = $configurationRepository->findOneBy([]);
        $this->templateRepository = $configurationEntityManager->getRepository(Template::class);
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
            ->disableUrlSignatures()
            ->setTitle('GameSites');
    }

    public function configureMenuItems(): iterable
    {
        if ($this->serverResponse && $this->serverResponse['expireDate'] <= date('Y-m-d')) {
            return [];
        }

        yield MenuItem::section('Main');
        yield MenuItem::linkToRoute('Console', 'fa fa-terminal', 'console');
        yield MenuItem::linkToRoute('Changelog', 'fab fa-readme', 'changes');
        yield MenuItem::linkToRoute('2FA Google', 'fab fa-google', 'user2fa');
        yield MenuItem::linkToRoute('Menedżer haseł', 'fas fa-key', 'admin_password_manager');

        yield MenuItem::section('Main content');
        yield MenuItem::linkToCrud('Image Repository', 'fas fa-image', Image::class);
        yield MenuItem::linkToCrud('Contact', 'fas fa-address-book', Contact::class);
        yield MenuItem::linkToCrud('Additional', 'fa fa-plus', Additional::class);
        yield MenuItem::linkToCrud('Rule', 'fa fa-book', Rule::class);

        $modules = $this->templateRepository->find($this->configuration->getTemplate())->getModules();

        /** @var Module $module */
        foreach ($modules as $module) {
            $activeSections[$module->getSection()->getName()][] = $module;
        }

        foreach ($activeSections ?? [] as $section => $modules) {
            yield MenuItem::section($section);
            foreach ($modules as $module) {
                yield MenuItem::linkToCrud($module->getName(), $module->getIcon(), 'App\Entity\\' . $module->getEntity());
            }
        }

//        yield MenuItem::section('Content');
//        yield MenuItem::linkToCrud('Articles', 'fa fa-newspaper', Article::class);
//        yield MenuItem::linkToCrud('Rank', 'fas fa-users', Rank::class);
//        yield MenuItem::linkToCrud('Bans', 'fas fa-ban', Bans::class);
//        yield MenuItem::linkToCrud('Administration', 'fas fa-user-shield', Administration::class);
//
//        yield MenuItem::section('Item Shop');
//        yield MenuItem::linkToCrud('Item', 'fas fa-shopping-cart', Item::class);
//        yield MenuItem::linkToCrud('Payment', 'fas fa-money-bill', Payment::class);
//        yield MenuItem::linkToCrud('Voucher', 'fas fa-receipt', Voucher::class);
//        yield MenuItem::linkToCrud('PaySafeCard', 'fas fa-lock', PaySafeCard::class);
//        yield MenuItem::linkToCrud('History', 'fa fa-history', ItemHistory::class);
//
//        yield MenuItem::section('Settings');
//        yield MenuItem::linkToCrud('Server', 'fas fa-server', Server::class);
//        yield MenuItem::linkToCrud('Guild Item', 'fas fa-sitemap', GuildItem::class);
//        yield MenuItem::linkToCrud('Redirect Link', 'fas fa-link', Link::class);
//        yield MenuItem::linkToCrud('Heading', 'fas fa-heading', Head::class);

        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);

        yield MenuItem::section('Advanced');
        yield MenuItem::linkToCrud('Configuration', 'fas fa-cog', Configuration::class);
        yield MenuItem::linkToCrud('SEO', 'fas fa-heading', Head::class);

    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setAvatarUrl('https://crafthead.net/cube/' . ($this->getUser()->getNick() ?: 'steve'))
            ->setName($this->getUser()->getNick() ?: $this->getUser()->getUserIdentifier());
    }
}