<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240701100259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, company_name VARCHAR(255) NOT NULL, address1 VARCHAR(255) NOT NULL, zipcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_C7440455F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_case (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, client_id INT NOT NULL, reference VARCHAR(16) NOT NULL, project_name VARCHAR(255) NOT NULL, address1 VARCHAR(255) NOT NULL, zipcode VARCHAR(16) NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, signed_at DATETIME DEFAULT NULL, INDEX IDX_A9945A66F92F3E70 (country_id), INDEX IDX_A9945A6619EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_case_partner_contact (client_case_id INT NOT NULL, partner_contact_id INT NOT NULL, INDEX IDX_1DF927119D2D9088 (client_case_id), INDEX IDX_1DF92711FD5026AC (partner_contact_id), PRIMARY KEY(client_case_id, partner_contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_case_client_contact (client_case_id INT NOT NULL, client_contact_id INT NOT NULL, INDEX IDX_6655180F9D2D9088 (client_case_id), INDEX IDX_6655180F77F5180B (client_contact_id), PRIMARY KEY(client_case_id, client_contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_contact (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, job_title_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_1E5FA24519EB6921 (client_id), INDEX IDX_1E5FA2456DD822C6 (job_title_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_job_title (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drink (id INT AUTO_INCREMENT NOT NULL, product VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, sugar TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, job_title_id INT NOT NULL, company_name VARCHAR(255) NOT NULL, address1 VARCHAR(255) NOT NULL, zipcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_312B3E16F92F3E70 (country_id), INDEX IDX_312B3E166DD822C6 (job_title_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner_contact (id INT AUTO_INCREMENT NOT NULL, partner_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_8B8885259393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner_job_title (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE client_case ADD CONSTRAINT FK_A9945A66F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE client_case ADD CONSTRAINT FK_A9945A6619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE client_case_partner_contact ADD CONSTRAINT FK_1DF927119D2D9088 FOREIGN KEY (client_case_id) REFERENCES client_case (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_case_partner_contact ADD CONSTRAINT FK_1DF92711FD5026AC FOREIGN KEY (partner_contact_id) REFERENCES partner_contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_case_client_contact ADD CONSTRAINT FK_6655180F9D2D9088 FOREIGN KEY (client_case_id) REFERENCES client_case (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_case_client_contact ADD CONSTRAINT FK_6655180F77F5180B FOREIGN KEY (client_contact_id) REFERENCES client_contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_contact ADD CONSTRAINT FK_1E5FA24519EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE client_contact ADD CONSTRAINT FK_1E5FA2456DD822C6 FOREIGN KEY (job_title_id) REFERENCES client_job_title (id)');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E16F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E166DD822C6 FOREIGN KEY (job_title_id) REFERENCES partner_job_title (id)');
        $this->addSql('ALTER TABLE partner_contact ADD CONSTRAINT FK_8B8885259393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455F92F3E70');
        $this->addSql('ALTER TABLE client_case DROP FOREIGN KEY FK_A9945A66F92F3E70');
        $this->addSql('ALTER TABLE client_case DROP FOREIGN KEY FK_A9945A6619EB6921');
        $this->addSql('ALTER TABLE client_case_partner_contact DROP FOREIGN KEY FK_1DF927119D2D9088');
        $this->addSql('ALTER TABLE client_case_partner_contact DROP FOREIGN KEY FK_1DF92711FD5026AC');
        $this->addSql('ALTER TABLE client_case_client_contact DROP FOREIGN KEY FK_6655180F9D2D9088');
        $this->addSql('ALTER TABLE client_case_client_contact DROP FOREIGN KEY FK_6655180F77F5180B');
        $this->addSql('ALTER TABLE client_contact DROP FOREIGN KEY FK_1E5FA24519EB6921');
        $this->addSql('ALTER TABLE client_contact DROP FOREIGN KEY FK_1E5FA2456DD822C6');
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E16F92F3E70');
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E166DD822C6');
        $this->addSql('ALTER TABLE partner_contact DROP FOREIGN KEY FK_8B8885259393F8FE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_case');
        $this->addSql('DROP TABLE client_case_partner_contact');
        $this->addSql('DROP TABLE client_case_client_contact');
        $this->addSql('DROP TABLE client_contact');
        $this->addSql('DROP TABLE client_job_title');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE drink');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE partner_contact');
        $this->addSql('DROP TABLE partner_job_title');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE `user`');
    }
}
