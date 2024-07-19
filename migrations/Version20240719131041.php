<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719131041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, client_case_id INT NOT NULL, report_type_id INT NOT NULL, reference VARCHAR(255) NOT NULL, draft TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C42F7784B03A8386 (created_by_id), INDEX IDX_C42F77849D2D9088 (client_case_id), INDEX IDX_C42F7784A5D5F193 (report_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77849D2D9088 FOREIGN KEY (client_case_id) REFERENCES client_case (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A5D5F193 FOREIGN KEY (report_type_id) REFERENCES report_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784B03A8386');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77849D2D9088');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A5D5F193');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE report_type');
    }
}
