<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240620204138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE partner_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE partner_contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE partner (id INT NOT NULL, company_name VARCHAR(255) NOT NULL, address1 VARCHAR(255) NOT NULL, zipcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE partner_contact (id INT NOT NULL, partner_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8B8885259393F8FE ON partner_contact (partner_id)');
        $this->addSql('ALTER TABLE partner_contact ADD CONSTRAINT FK_8B8885259393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE partner_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE partner_contact_id_seq CASCADE');
        $this->addSql('ALTER TABLE partner_contact DROP CONSTRAINT FK_8B8885259393F8FE');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE partner_contact');
    }
}
