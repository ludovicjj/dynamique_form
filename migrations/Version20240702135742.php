<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702135742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_case_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_case ADD client_case_status_id INT NOT NULL');
        $this->addSql('ALTER TABLE client_case ADD CONSTRAINT FK_A9945A66641732C9 FOREIGN KEY (client_case_status_id) REFERENCES client_case_status (id)');
        $this->addSql('CREATE INDEX IDX_A9945A66641732C9 ON client_case (client_case_status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_case DROP FOREIGN KEY FK_A9945A66641732C9');
        $this->addSql('DROP TABLE client_case_status');
        $this->addSql('DROP INDEX IDX_A9945A66641732C9 ON client_case');
        $this->addSql('ALTER TABLE client_case DROP client_case_status_id');
    }
}
