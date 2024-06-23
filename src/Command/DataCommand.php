<?php

namespace App\Command;

use App\Factory\ClientCaseFactory;
use App\Factory\CountryFactory;
use App\Factory\PartnerContactFactory;
use App\Factory\PartnerFactory;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:import-data')]
class DataCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Init import data...');
        $this->purgeTable();

        $this->importCountry();
        $this->importUser();
        $this->importClientCase();
        $this->importPartner();
        $this->importPartnerContact();

        $io->success('Data imported with success');
        return Command::SUCCESS;
    }

    public function importCountry(): void
    {
        CountryFactory::createSequence(
            [
                ['name' => 'France'],
                ['name' => 'Allemagne'],
                ['name' => 'Espagne'],
                ['name' => 'Italie'],
            ]
        );
    }
    private function importUser(): void
    {
        UserFactory::new(['email' => 'john@exemple.com'])->asAdmin()->create();
    }

    private function importClientCase(): void
    {
        ClientCaseFactory::createSequence(
            function() {
                foreach (range(1, 200) as $i) {
                    yield ['country' => CountryFactory::random()];
                }
            }
        );
    }

    private function importPartner(): void
    {
        PartnerFactory::createSequence(
            function() {
                foreach (range(1, 3) as $i) {
                    yield ['country' => CountryFactory::random()];
                }
            }
        );
    }

    private function importPartnerContact(): void
    {
        PartnerContactFactory::createSequence(
            function() {
                foreach (range(1, 15) as $i) {
                    yield ['partner' => PartnerFactory::random()];
                }
            }
        );
    }

    private function purgeTable()
    {
        $connection = $this->entityManager->getConnection();

        $platform = $connection->getDatabasePlatform();

        $tables = [
            'user', 'client_case', 'partner', 'partner_contact', 'country'
        ];

        //$connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {
            $connection->executeQuery($platform->getTruncateTableSQL($table, true));
        }

    }
}