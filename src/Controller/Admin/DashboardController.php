<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->redirect($this->adminUrlGenerator->setController(EintragCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
                        ->setTitle('Berichtsheft');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Einträge', 'fa fa-home');
        yield MenuItem::linkToRoute('Alle Einträge', 'fa fa-list', 'app_show_all');
        yield MenuItem::linkToRoute('Drucken', 'fa fa-print', 'app_print');
    }
}
