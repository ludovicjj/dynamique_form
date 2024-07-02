<?php

namespace App\Command;

use App\Factory\BuildingCategoryFactory;
use App\Factory\ClientCaseFactory;
use App\Factory\ClientCaseStatusFactory;
use App\Factory\ClientContactFactory;
use App\Factory\ClientFactory;
use App\Factory\ClientJobTitleFactory;
use App\Factory\CountryFactory;
use App\Factory\MissionFactory;
use App\Factory\PartnerContactFactory;
use App\Factory\PartnerFactory;
use App\Factory\PartnerJobTitleFactory;
use App\Factory\ProjectFeatureFactory;
use App\Factory\UserFactory;
use App\Factory\UserJobTitleFactory;
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

        $progressBar = $io->createProgressBar(13);

        $this->importUserJobTitle();
        $progressBar->advance();

        $this->importUser();
        $progressBar->advance();

        $this->importCountry();
        $progressBar->advance();

        $this->importMission();
        $progressBar->advance();

        $this->importProjectFeature();
        $progressBar->advance();

        $this->importBuildingCategory();
        $progressBar->advance();

        $this->importClientCaseStatus();
        $progressBar->advance();

        // ClientCase
        //$this->importClientCase();

        // Partner
        $this->importPartnerJobTitle();
        $progressBar->advance();

        $this->importPartner();
        $progressBar->advance();

        $this->importPartnerContact();
        $progressBar->advance();

        // Client
        $this->importClientJobTitle();
        $progressBar->advance();

        $this->importClient();
        $progressBar->advance();

        $this->importClientContact();
        $progressBar->finish();

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

    private function importMission(): void
    {
        MissionFactory::createSequence(
            [
                ['code' => 'Type L', 'name' => 'L', 'description' => "Relative à la solidité des ouvrages et éléménts d'équipement indissociables."],
                ['code' => 'Type S', 'name' => 'S', 'description' => "Relative à la sécurité des personnes dans les constructions."],
                ['code' => 'Type SH', 'name' => 'SH', 'description' => "Relative à la sécurité des personnes dans les bâtiments d'habitation."],
                ['code' => 'Type STI', 'name' => 'STI', 'description' => "Relative à la sécurité des personnes dans les bâtiments tertiaires."],
                ['code' => 'Type SEI', 'name' => 'SEI', 'description' => "Relative à la sécurité des personnes dans les ERP et IGH."],
                ['code' => 'Type PS', 'name' => 'PS', 'description' => "Relative à la sécurité des personnes dans les constructions en cas de séismes."],
                ['code' => 'Type P1', 'name' => 'P1', 'description' => "Relative à la solidité des éléments d'équipement non indissociablement liés."],
                ['code' => 'Type F', 'name' => 'F', 'description' => "Relative au fonctionnement des installations."],
                ['code' => 'Type Ph', 'name' => 'Ph', 'description' => "Relative à l'isolation acoustiques des bâtiments."],
                ['code' => 'Type Phh', 'name' => 'Phh', 'description' => "Relative à l'isolation acoustiques des bâtiments d'habitation."],
                ['code' => 'Type Pha', 'name' => 'Pha', 'description' => "Relative à l'isolation acoustiques des bâtiments autres qu'a usage d'habitation."],
                ['code' => 'Type MES', 'name' => 'MES', 'description' => "Relative acoustiques et attestation de la prise en compte de la réglementation acoustique."],
                ['code' => 'Type Th', 'name' => 'Th', 'description' => "Relative à l'isolation thermique et aux économies d'énergie."],
                ['code' => 'Type HAND', 'name' => 'HAND', 'description' => "Relative à l'accessibilité des constructions pour les personne handicapées."],
                ['code' => 'Type ATHAND', 'name' => 'ATHAND', 'description' => "Attestation vérification de l'accessibilité aux personnes handicapées."],
                ['code' => 'Type Brd', 'name' => 'Brd', 'description' => "Relative au transport des brancards dans les constructions."],
                ['code' => 'Type LE', 'name' => 'LE', 'description' => "Relative à la solidité des existants."],
                ['code' => 'Type Av', 'name' => 'Av', 'description' => "Relative à la stabilité des avoisinants."],
                ['code' => 'Type GTB', 'name' => 'GTB', 'description' => "Relative à la Gestion Technique de Bâtiment."],
                ['code' => 'Type Env', 'name' => 'Env', 'description' => "Relative à l'environnement."],
            ]
        );
    }

    private function importProjectFeature(): void
    {
        ProjectFeatureFactory::createSequence([
            ['name' => "Construction neuve"],
            ['name' => "Restructuration"],
            ['name' => "Extension"],
            ['name' => "Aménagement intérieur"],
            ['name' => "Rénovation énergétique"],
            ['name' => "Démolition"],
        ]);
    }

    private function importBuildingCategory(): void
    {
        BuildingCategoryFactory::createSequence([
           ['code' => 'HAB', 'description' => 'Habitation'],
           ['code' => 'ERT', 'description' => 'Etablissement recevant des travailleurs'],
           ['code' => 'ERP', 'description' => 'Etablissement recevant du public'],
           ['code' => 'IGH', 'description' => 'Immeuble de grande hauteur'],
           ['code' => 'ICPE', 'description' => "Installation Classée pour la Protection de l'Environnement"],
           ['code' => 'BUR', 'description' => 'Bureau'],
           ['code' => 'IND', 'description' => 'Industriel'],
        ]);
    }

    private function importClientCaseStatus(): void
    {
        ClientCaseStatusFactory::createSequence([
            ['name' => 'Conception'],
            ['name' => 'Travaux'],
            ['name' => 'Réception'],
        ]);
    }

    private function importUserJobTitle(): void
    {
        UserJobTitleFactory::createSequence([
            ['name' => 'Président'],
            ['name' => 'Directeur Général'],
            ['name' => 'Assistante'],
            ['name' => "Responsable d'affaires"],
            ['name' => 'Assistante Gestion'],
            ['name' => "Responsable qualité"],
            ['name' => "Responsable AO"],
            ['name' => "Directeur d'agence"],
            ['name' => "Spécialiste électicité"],
            ['name' => "Responsable Technique"],
            ['name' => "Apprenti"],
            ['name' => "Spécialiste thermique Fuilde"],
            ['name' => "Directeur adjoint"],
            ['name' => "Directrice adjointe"],
            ['name' => "Coordinateur SPS"],
        ]);
    }

    private function importUser(): void
    {
        UserFactory::new([
            'email' => 'john@exemple.com',
            'firstname' => 'john',
            'lastname' => 'doe',
            'jobTitle' => UserJobTitleFactory::random()
        ])->asAdmin()->create();

        UserFactory::createSequence(
            function() {
                foreach (range(1, 50) as $i) {
                    yield ['jobTitle' => UserJobTitleFactory::random()];
                }
            }
        );
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
            'building_category',
            'client',
            'client_case',
            'client_case_client_contact',
            'client_case_mission',
            'client_case_partner_contact',
            'client_case_user',
            'client_contact',
            'client_job_title',
            'country',
            'mission',
            'partner',
            'partner_contact',
            'partner_job_title',
            'project_feature',
            'user',
            'user_job_title'
        ];

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {
            $connection->executeQuery($platform->getTruncateTableSQL($table, true));
        }

    }
}