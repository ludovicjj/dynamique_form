<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240701190623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_case ADD directory_name VARCHAR(255) DEFAULT NULL, ADD build_started_at DATETIME DEFAULT NULL, ADD build_finished_at DATETIME DEFAULT NULL, ADD agreement_amount VARCHAR(255) DEFAULT NULL, ADD last_know_cost VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_case DROP directory_name, DROP build_started_at, DROP build_finished_at, DROP agreement_amount, DROP last_know_cost, DROP description');
    }
}
