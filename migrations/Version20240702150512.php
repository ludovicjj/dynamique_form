<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702150512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_case_project_feature (client_case_id INT NOT NULL, project_feature_id INT NOT NULL, INDEX IDX_1FB8DB379D2D9088 (client_case_id), INDEX IDX_1FB8DB377842C34B (project_feature_id), PRIMARY KEY(client_case_id, project_feature_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_case_project_feature ADD CONSTRAINT FK_1FB8DB379D2D9088 FOREIGN KEY (client_case_id) REFERENCES client_case (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_case_project_feature ADD CONSTRAINT FK_1FB8DB377842C34B FOREIGN KEY (project_feature_id) REFERENCES project_feature (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_case_project_feature DROP FOREIGN KEY FK_1FB8DB379D2D9088');
        $this->addSql('ALTER TABLE client_case_project_feature DROP FOREIGN KEY FK_1FB8DB377842C34B');
        $this->addSql('DROP TABLE client_case_project_feature');
    }
}
