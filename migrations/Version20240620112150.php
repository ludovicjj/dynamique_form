<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240620112150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init ClientCase Entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE client_case_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE client_case (id INT NOT NULL, reference VARCHAR(16) NOT NULL, project_name VARCHAR(255) NOT NULL, address1 VARCHAR(255) NOT NULL, zipcode VARCHAR(16) NOT NULL, city VARCHAR(255) NOT NULL, signed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE client_case_id_seq CASCADE');
        $this->addSql('DROP TABLE client_case');
    }
}
