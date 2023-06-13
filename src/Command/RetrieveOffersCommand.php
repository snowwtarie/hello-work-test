<?php

namespace App\Command;

use App\Repository\OffreRepository;
use App\Service\HttpClient;
use Exception;
use PDOException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:offres:sync',
    description: 'Récupérer les offres',
    hidden: false
)]
class RetrieveOffersCommand extends Command
{
    protected function configure()
    {
        $this->setHelp(
            'Cette commande permet de récupérer les offres d\'emplois pour les villes de Rennes, Bordeaux et Paris'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new HttpClient();
        $offres = $client->getOffres('', '35238,33063,75056');
        $db = new OffreRepository();
        $offreCount = 0;

        foreach ($offres as $offre) {
            try {
                $db->addOffre($offre);
                $offreCount++;
            } catch (PDOException $e) {
            }
        }

        $output->writeln($offreCount . ' offres ont été ajoutées.');
    
        return Command::SUCCESS;
    }
}
