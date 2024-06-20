<?php

namespace App\Command;

use App\Entity\ClientCase;
use App\Entity\Partner;
use App\Entity\PartnerContact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DateTime;

#[AsCommand(name: 'app:import-data')]
class ClientCaseCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $firstNames = [
            'Alexandre', 'Benoit', 'Catherine', 'David', 'Emilie',
            'François', 'Gabrielle', 'Hélène', 'Isabelle', 'Jacques',
            'Karine', 'Laurent', 'Marianne', 'Nicolas', 'Olivier'
        ];

        $lastNames = [
            'Martin', 'Bernard', 'Thomas', 'Petit', 'Robert',
            'Richard', 'Durand', 'Dubois', 'Moreau', 'Laurent',
            'Simon', 'Michel', 'Lefebvre', 'Leroy', 'Roux'
        ];

        $companyNames = [
            'BlueHorizon Media', 'Quantum Financial Services', 'UrbanSmart Development',
            'NextGen Communications', 'BrightFuture Enterprises', 'EcoEnergy Innovations',
            'AeroSpace Dynamics', 'GlobalTrade Ventures', 'GreenWave Technologies', 'TechNova Solutions'
        ];

        $this->purgeTable();

        for ($i = 1; $i <= 10; $i++) {
            $clientCase = new ClientCase();

            $clientCase
                ->setSignedAt(new DateTime())
                ->setProjectName('name-' .$i)
                ->setReference('W056P018-' .$i)
                ->setCity('city-' .$i)
                ->setAddress1('address-' .$i)
                ->setZipcode('7500'.$i);

            $this->entityManager->persist($clientCase);
        }

        $partners = [];
        for($i = 1; $i <= 3; $i++) {
            $partner = new Partner();
            $partner
                ->setCompanyName($companyNames[$i])
                ->setAddress1($i.' rue de la liberté')
                ->setEmail('partner'.$i.'@contact.fr')
                ->setPhone('06123456789')
                ->setCity('paris')
                ->setZipcode('75001')
                ->setSiret('123456789');

            $partners[] = $partner;

            $this->entityManager->persist($partner);
        }

        for($i = 0; $i < 15; $i++) {
            $partnerContact = new PartnerContact();
            $partnerContact
                ->setEmail('partner-contact'.$i.'@contact.fr')
                ->setFirstname($firstNames[$i])
                ->setLastname($lastNames[$i])
                ->setPhone('06123456789');

            $partnerKey = array_rand($partners);
            $partner = $partners[$partnerKey];

            $partnerContact->setPartner($partner);

            $this->entityManager->persist($partnerContact);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function purgeTable()
    {
        $connection = $this->entityManager->getConnection();

        $platform = $connection->getDatabasePlatform();

        $tables = [
            'client_case', 'partner', 'partner_contact'
        ];

        //$connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {
            $connection->executeQuery($platform->getTruncateTableSQL($table, true));
        }

    }
}