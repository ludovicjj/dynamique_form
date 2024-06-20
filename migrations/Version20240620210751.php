<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240620210751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_case_partner_contact (client_case_id INT NOT NULL, partner_contact_id INT NOT NULL, PRIMARY KEY(client_case_id, partner_contact_id))');
        $this->addSql('CREATE INDEX IDX_1DF927119D2D9088 ON client_case_partner_contact (client_case_id)');
        $this->addSql('CREATE INDEX IDX_1DF92711FD5026AC ON client_case_partner_contact (partner_contact_id)');
        $this->addSql('ALTER TABLE client_case_partner_contact ADD CONSTRAINT FK_1DF927119D2D9088 FOREIGN KEY (client_case_id) REFERENCES client_case (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_case_partner_contact ADD CONSTRAINT FK_1DF92711FD5026AC FOREIGN KEY (partner_contact_id) REFERENCES partner_contact (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE client_case_partner_contact DROP CONSTRAINT FK_1DF927119D2D9088');
        $this->addSql('ALTER TABLE client_case_partner_contact DROP CONSTRAINT FK_1DF92711FD5026AC');
        $this->addSql('DROP TABLE client_case_partner_contact');
    }
}
