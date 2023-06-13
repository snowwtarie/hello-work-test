<?php

namespace App\Command;

use App\Repository\OffreRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:offres:list',
    description: 'Afficher les offres',
    hidden: false
)]
class ShowOffersCommand extends Command
{
    protected function configure()
    {
        $this->setHelp(
            'Cette commande permet de récupérer les offres d\'emplois pour les villes de Rennes, Bordeaux et Paris'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db = new OffreRepository();
        $offres = $db->findAll('offre');

        foreach ($offres as $offre) {
            $output->writeln(sprintf(
                'Offre %s - Type contrat: %s - Entreprise: %s - Pays - %s',
                $offre->getId(),
                $offre->getTypeContrat(),
                empty($offre->getNomEntreprise()) ? 'N\A' : $offre->getNomEntreprise(),
                '?'
            ));
        }


        return Command::SUCCESS;
    }
}
