<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240629122420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Bind PartnerJobTitle to Partner';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner ADD job_title_id INT NOT NULL');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E166DD822C6 FOREIGN KEY (job_title_id) REFERENCES partner_job_title (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_312B3E166DD822C6 ON partner (job_title_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE partner DROP CONSTRAINT FK_312B3E166DD822C6');
        $this->addSql('DROP INDEX IDX_312B3E166DD822C6');
        $this->addSql('ALTER TABLE partner DROP job_title_id');
    }
}
