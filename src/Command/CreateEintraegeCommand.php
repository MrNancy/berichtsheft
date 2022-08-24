<?php

namespace App\Command;

use App\Entity\Eintrag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'CreateEintraege',
    description: 'Add a short description for your command',
)]
class CreateEintraegeCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $date1 = new \DateTime('03.05.2021');
        $date2 = new \DateTime('28.08.2022');

        for ($fromDate = $date1; $fromDate <= $date2; $fromDate->modify('+1 week')) {
            $randomDay = random_int(6, 7);
            $toDate = (clone $fromDate)->modify('+4 day');
            $date = (clone $fromDate)->modify('+' . $randomDay . ' day');

            $eintrag = (new Eintrag())
                ->setFromDate($fromDate)
                ->setToDate($toDate)
                ->setDate($date);

            $eintragRepository = $this->entityManager->getRepository(Eintrag::class);
            $eintragFound = $eintragRepository->findOneBy(['fromDate' => $fromDate]);
            if ($eintragFound) {
                continue;
            }

            $this->entityManager->persist($eintrag);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}
