<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240704102259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init Document Entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, added_by_id INT NOT NULL, created_by_id INT DEFAULT NULL, client_case_id INT NOT NULL, name VARCHAR(255) NOT NULL, filepath LONGTEXT NOT NULL, created_at DATETIME NOT NULL, added_at DATETIME DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, tag VARCHAR(255) DEFAULT NULL, indice VARCHAR(8) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_D8698A7655B127A4 (added_by_id), INDEX IDX_D8698A76B03A8386 (created_by_id), INDEX IDX_D8698A769D2D9088 (client_case_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7655B127A4 FOREIGN KEY (added_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76B03A8386 FOREIGN KEY (created_by_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A769D2D9088 FOREIGN KEY (client_case_id) REFERENCES client_case (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7655B127A4');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76B03A8386');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A769D2D9088');
        $this->addSql('DROP TABLE document');
    }
}
