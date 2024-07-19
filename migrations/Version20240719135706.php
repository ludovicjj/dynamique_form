<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719135706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, report_id INT NOT NULL, previous_id INT DEFAULT NULL, review_group_id INT NOT NULL, review_value_id INT NOT NULL, number INT NOT NULL, observation LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, visited_at DATETIME DEFAULT NULL, position INT NOT NULL, archived TINYINT(1) NOT NULL, INDEX IDX_794381C6B03A8386 (created_by_id), INDEX IDX_794381C64BD2A4C0 (report_id), INDEX IDX_794381C62DE62210 (previous_id), INDEX IDX_794381C6DD16504C (review_group_id), INDEX IDX_794381C6DA6232A9 (review_value_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review_document (review_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_FB2B037D3E2E969B (review_id), INDEX IDX_FB2B037DC33F7837 (document_id), PRIMARY KEY(review_id, document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review_value (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C64BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C62DE62210 FOREIGN KEY (previous_id) REFERENCES review (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6DD16504C FOREIGN KEY (review_group_id) REFERENCES review_group (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6DA6232A9 FOREIGN KEY (review_value_id) REFERENCES review_value (id)');
        $this->addSql('ALTER TABLE review_document ADD CONSTRAINT FK_FB2B037D3E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review_document ADD CONSTRAINT FK_FB2B037DC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6B03A8386');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C64BD2A4C0');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C62DE62210');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6DD16504C');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6DA6232A9');
        $this->addSql('ALTER TABLE review_document DROP FOREIGN KEY FK_FB2B037D3E2E969B');
        $this->addSql('ALTER TABLE review_document DROP FOREIGN KEY FK_FB2B037DC33F7837');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE review_document');
        $this->addSql('DROP TABLE review_group');
        $this->addSql('DROP TABLE review_value');
    }
}
