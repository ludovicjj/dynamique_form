<?php

namespace App\Command;

use App\Factory\ClientCaseFactory;
use App\Factory\ClientContactFactory;
use App\Factory\ClientFactory;
use App\Factory\ClientJobTitleFactory;
use App\Factory\CountryFactory;
use App\Factory\PartnerContactFactory;
use App\Factory\PartnerFactory;
use App\Factory\PartnerJobTitleFactory;
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

        $this->importUser();
        $this->importCountry();

        // ClientCase
        //$this->importClientCase();

        // Partner
        $this->importPartnerJobTitle();
        $this->importPartner();
        $this->importPartnerContact();

        // Client
        $this->importClientJobTitle();
        $this->importClient();
        $this->importClientContact();

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

    public function importPartnerJobTitle(): void
    {
        PartnerJobTitleFactory::createSequence(
            function () {
                foreach (PartnerJobTitleFactory::JOBS as $job) {
                    yield ['name' => $job];
                }
            }
        );
    }

    private function importPartner(): void
    {
        PartnerFactory::createSequence(
            function() {
                foreach (range(1, 3) as $i) {
                    yield [
                        'country' => CountryFactory::random(),
                        'jobTitle' => PartnerJobTitleFactory::random()
                    ];
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

    private function importClientJobTitle(): void
    {
        ClientJobTitleFactory::createSequence(
            function () {
                foreach (ClientJobTitleFactory::JOBS as $job) {
                    yield ['name' => $job];
                }
            }
        );
    }

    private function importClient(): void
    {
        ClientFactory::createSequence(
            function() {
                foreach (range(1, 3) as $i) {
                    yield ['country' => CountryFactory::random()];
                }
            }
        );
    }

    private function importClientContact(): void
    {
        ClientContactFactory::createSequence(
            function() {
                foreach (range(1, 15) as $i) {
                    yield [
                        'client' => ClientFactory::random(),
                        'jobTitle' => ClientJobTitleFactory::random()
                    ];
                }
            }
        );
    }

    private function purgeTable()
    {
        $connection = $this->entityManager->getConnection();

        $platform = $connection->getDatabasePlatform();

        $tables = [
            'user', 'client_case', 'country',
            'client', 'client_contact', 'client_job_title',
            'partner', 'partner_contact', 'partner_job_title'
        ];

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {
            $connection->executeQuery($platform->getTruncateTableSQL($table, true));
        }

    }
}