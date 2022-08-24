<?php

namespace App\Controller;

use App\Entity\Eintrag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowController extends AbstractController
{
    #[Route('/anzeigen/{_eintragId}', name: 'app_show')]
    public function show(EntityManagerInterface $entityManager, Request $request): Response
    {
        $id = $request->get('_eintragId');

        $eintrag = $entityManager
            ->getRepository(Eintrag::class)
            ->findOneBy(['id' => $id]);

        return $this->render('show/index.html.twig', [
            'eintrag' => $eintrag,
        ]);
    }

    #[Route('/alle/', name: 'app_show_all')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $eintraege = $entityManager
            ->getRepository(Eintrag::class)
            ->findBy([], ['fromDate' => 'ASC']);

        return $this->render('show_all/index.html.twig', [
            'eintraege' => $eintraege,
        ]);
    }

    #[Route('/drucken/', name: 'app_print')]
    public function print(EntityManagerInterface $entityManager): Response
    {

        $eintraege = $entityManager
            ->getRepository(Eintrag::class)
            ->findBy([], ['fromDate' => 'ASC']);

        return $this->render('print/index.html.twig', [
            'eintraege' => $eintraege,
        ]);
    }
}
