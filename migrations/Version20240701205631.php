<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240701205631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD job_title_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496DD822C6 FOREIGN KEY (job_title_id) REFERENCES user_job_title (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6496DD822C6 ON user (job_title_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6496DD822C6');
        $this->addSql('DROP INDEX IDX_8D93D6496DD822C6 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP job_title_id');
    }
}
