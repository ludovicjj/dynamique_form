<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702201840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_case DROP FOREIGN KEY FK_A9945A66727ACA70');
        $this->addSql('DROP INDEX IDX_A9945A66727ACA70 ON client_case');
        $this->addSql('ALTER TABLE client_case CHANGE parent_id parent_case_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client_case ADD CONSTRAINT FK_A9945A66BD58A0B3 FOREIGN KEY (parent_case_id) REFERENCES client_case (id)');
        $this->addSql('CREATE INDEX IDX_A9945A66BD58A0B3 ON client_case (parent_case_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_case DROP FOREIGN KEY FK_A9945A66BD58A0B3');
        $this->addSql('DROP INDEX IDX_A9945A66BD58A0B3 ON client_case');
        $this->addSql('ALTER TABLE client_case CHANGE parent_case_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client_case ADD CONSTRAINT FK_A9945A66727ACA70 FOREIGN KEY (parent_id) REFERENCES client_case (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A9945A66727ACA70 ON client_case (parent_id)');
    }
}
