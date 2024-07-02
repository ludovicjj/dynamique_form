<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702132342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_case_user (client_case_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E7ABDCF59D2D9088 (client_case_id), INDEX IDX_E7ABDCF5A76ED395 (user_id), PRIMARY KEY(client_case_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_case_user ADD CONSTRAINT FK_E7ABDCF59D2D9088 FOREIGN KEY (client_case_id) REFERENCES client_case (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_case_user ADD CONSTRAINT FK_E7ABDCF5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_case ADD created_by_id INT NOT NULL, ADD parent_id INT DEFAULT NULL, ADD is_draft TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE client_case ADD CONSTRAINT FK_A9945A66B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE client_case ADD CONSTRAINT FK_A9945A66727ACA70 FOREIGN KEY (parent_id) REFERENCES client_case (id)');
        $this->addSql('CREATE INDEX IDX_A9945A66B03A8386 ON client_case (created_by_id)');
        $this->addSql('CREATE INDEX IDX_A9945A66727ACA70 ON client_case (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_case_user DROP FOREIGN KEY FK_E7ABDCF59D2D9088');
        $this->addSql('ALTER TABLE client_case_user DROP FOREIGN KEY FK_E7ABDCF5A76ED395');
        $this->addSql('DROP TABLE client_case_user');
        $this->addSql('ALTER TABLE client_case DROP FOREIGN KEY FK_A9945A66B03A8386');
        $this->addSql('ALTER TABLE client_case DROP FOREIGN KEY FK_A9945A66727ACA70');
        $this->addSql('DROP INDEX IDX_A9945A66B03A8386 ON client_case');
        $this->addSql('DROP INDEX IDX_A9945A66727ACA70 ON client_case');
        $this->addSql('ALTER TABLE client_case DROP created_by_id, DROP parent_id, DROP is_draft');
    }
}
