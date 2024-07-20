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
use App\Factory\ReportStatusFactory;
use App\Factory\ReportTypeFactory;
use App\Factory\ReviewGroupFactory;
use App\Factory\ReviewValueFactory;
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

        $progressBar = $io->createProgressBar(17);

        // user
        $this->importUserJobTitle();
        $this->importUser();
        $progressBar->advance(2);

        // Global
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

        // Report
        $this->importReportType();
        $this->importReportStatus();
        $progressBar->advance(2);

        // Review
        $this->importReviewGroup();
        $this->importReviewValue();
        $progressBar->advance(2);

        // Partner
        $this->importPartnerJobTitle();
        $this->importPartner();
        $this->importPartnerContact();
        $progressBar->advance(3);

        // Client
        $this->importClientJobTitle();
        $this->importClient();
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
            ['name' => 'Conception', 'code' => 'design'],
            ['name' => 'Travaux', 'code' => 'construction'],
            ['name' => 'Réception', 'code' => 'handover'],
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

    private function importReportType(): void
    {
        ReportTypeFactory::createSequence([
                ['name' => 'Avis sur AD', 'code' => 'AD'],
                ['name' => 'Avis sur FCE', 'code' => 'FCE'],
                ['name' => 'Avis sur SAv', 'code' => 'SAv'],
                ['name' => 'Avis sur RFCT', 'code' => 'RFCT'],
                ['name' => 'Avis sur Esquisse', 'code' => 'ESQ'],
                ['name' => 'Avis sur Diagnostic', 'code' => 'DIAG'],
                ['name' => 'Avis sur PC', 'code' => 'PC'],
                ['name' => 'Avis sur APS', 'code' => 'APS'],
                ['name' => 'Avis sur APD', 'code' => 'APD'],
                ['name' => 'Avis sur AVP', 'code' => 'AVP'],
                ['name' => 'Avis sur PRO', 'code' => 'PRO'],
                ['name' => 'Avis sur RICT', 'code' => 'RICT'],
                ['name' => 'Avis sur RVRAT', 'code' => 'RVRAT'],
        ]);
    }

    private function importReportStatus(): void
    {
        ReportStatusFactory::createSequence([
           ['name' => 'Brouillon', 'code' => 'draft'],
           ['name' => 'En attente de validation', 'code' => 'pending'],
           ['name' => 'Validé', 'code' => 'validate'],
        ]);
    }

    private function importReviewValue(): void
    {
        ReviewValueFactory::createSequence([
            ['name' => 'F'],
            ['name' => 'S'],
            ['name' => 'D'],
            ['name' => 'PM'],
            ['name' => 'SO'],
            ['name' => 'HM'],
        ]);
    }

    private function importReviewGroup(): void
    {
        ReviewGroupFactory::createSequence([
            ['name' => "Solidité"],
            ['name' => "Avoisinants"],
            ['name' => "Existants"],
            ['name' => "Géotechnique"],
            ['name' => "Voiries"],
            ['name' => "Réseaux enterrés"],
            ['name' => "Confortement de sol"],
            ['name' => "Fondations profondes"],
            ['name' => "Fondations superficielles"],
            ['name' => "Mur de soutènement"],
            ['name' => "Talus, remblais"],
            ['name' => "Dallage"],
            ['name' => "Radier"],
            ['name' => "Reprise en sous œuvre"],
            ['name' => "Superstructure béton"],
            ['name' => "Escaliers"],
            ['name' => "Gaine d'ascenseur"],
            ['name' => "Charpente métallique"],
            ['name' => "Charpente bois"],
            ['name' => "Ossature bois"],
            ['name' => "Structure mixte"],
            ['name' => "Couverture"],
            ['name' => "Cuvelage"],
            ['name' => "Etanchéité"],
            ['name' => "Verrière"],
            ['name' => "Descentes d'eaux pluviales"],
            ['name' => "Menuiseries extérieures"],
            ['name' => "Bardage"],
            ['name' => "Façades rideaux"],
            ['name' => "VEC VEA vitrages extérieurs collés/ attachés"],
            ['name' => "Panneaux préfabriqués de façade"],
            ['name' => "Murs de façade"],
            ['name' => "Enduits"],
            ['name' => "Isolation thermique par l'extérieur"],
            ['name' => "Revêtement de façade collé"],
            ['name' => "Revêtement de façade attaché"],
            ['name' => "Facade à ossature bois"],
            ['name' => "Cloisons"],
            ['name' => "Chapes"],
            ['name' => "Revêtement de sols durs"],
            ['name' => "Revêtement de sol souples"],
            ['name' => "Revêtement de sol résine"],
            ['name' => "Faux-plafonds"],
            ['name' => "Peinture"],
            ['name' => "Menuiseries intérieures"],
            ['name' => "Garde-corps"],
            ['name' => "Portes automatiques"],
            ['name' => "Appareils de levage"],
            ['name' => "Courant fort"],
            ['name' => "Courant faible"],
            ['name' => "Photovoltaïque"],
            ['name' => "Sonorisation"],
            ['name' => "Plomberie sanitaire"],
            ['name' => "Ventilation, climatisation, VMC"],
            ['name' => "Chauffage eau chaude"],
            ['name' => "Assainissement"],
            ['name' => "Air comprimé industriel"],
            ['name' => "Sécurité incendie"],
            ['name' => "Classement"],
            ['name' => "Desserte du bâtiment"],
            ['name' => "Isolement par rapport aux tiers"],
            ['name' => "Résistance au feu des structures"],
            ['name' => "Evacuation des PMR"],
            ['name' => "Couvertures"],
            ['name' => "Façades"],
            ['name' => "Distribution Intérieure"],
            ['name' => "Atriums"],
            ['name' => "Locaux à risques"],
            ['name' => "Conduits et gaines"],
            ['name' => "Dégagements"],
            ['name' => "Aménagements intérieurs"],
            ['name' => "Désenfumage"],
            ['name' => "Gaz combustibles"],
            ['name' => "Fluides médicaux"],
            ['name' => "Fluides spéciaux"],
            ['name' => "Ascenseurs et monte-charges"],
            ['name' => "Grandes cuisines"],
            ['name' => "Poteaux d'incendie"],
            ['name' => "Robinets d'incendie armés"],
            ['name' => "Colonnes sèches"],
            ['name' => "Colonnes en charge"],
            ['name' => "Extinction automatique à eau"],
            ['name' => "Extinction automatique à gaz"],
            ['name' => "Extincteurs"],
            ['name' => "Détection Incendie"],
            ['name' => "SSI"],
            ['name' => "Système d'alarme"],
            ['name' => "Dispositions particulières"],
            ['name' => "Accessibilité PMR"],
            ['name' => "Cheminements extérieurs"],
            ['name' => "Stationnement automobile"],
            ['name' => "Accès au bâtiment"],
            ['name' => "Circulations intérieures horizontales"],
            ['name' => "Circulations intérieures verticales"],
            ['name' => "Revêtements sols et plafonds"],
            ['name' => "Portes"],
            ['name' => "Locaux, équipements et dispositifs de commande"],
            ['name' => "Sanitaires"],
            ['name' => "Sorties"],
            ['name' => "Eclairage"],
            ['name' => "Accueil du public"],
            ['name' => "Tapis roulants, escaliers et plans inclinés"],
            ['name' => "Locaux d'hébergement"]
        ]);
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

    private function purgeTable(): void
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
            'client_case_project_feature',
            'client_case_status',
            'client_case_user',
            'client_contact',
            'client_job_title',
            'country',
            'document',
            'mission',
            'partner',
            'partner_contact',
            'partner_job_title',
            'project_feature',
            'report',
            'report_status',
            'report_type',
            'review',
            'review_document',
            'review_group',
            'review_value',
            'user',
            'user_job_title'
        ];

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {
            $connection->executeQuery($platform->getTruncateTableSQL($table, true));
        }

    }
}