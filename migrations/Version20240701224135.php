<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240701224135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_case ADD manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client_case ADD CONSTRAINT FK_A9945A66783E3463 FOREIGN KEY (manager_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_A9945A66783E3463 ON client_case (manager_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_case DROP FOREIGN KEY FK_A9945A66783E3463');
        $this->addSql('DROP INDEX IDX_A9945A66783E3463 ON client_case');
        $this->addSql('ALTER TABLE client_case DROP manager_id');
    }
}
