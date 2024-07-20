<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240720124649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report ADD report_status_id INT NOT NULL');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778445436DA5 FOREIGN KEY (report_status_id) REFERENCES report_status (id)');
        $this->addSql('CREATE INDEX IDX_C42F778445436DA5 ON report (report_status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778445436DA5');
        $this->addSql('DROP INDEX IDX_C42F778445436DA5 ON report');
        $this->addSql('ALTER TABLE report DROP report_status_id');
    }
}
