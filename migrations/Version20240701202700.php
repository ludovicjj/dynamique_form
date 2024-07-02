<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240701202700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_case_mission (client_case_id INT NOT NULL, mission_id INT NOT NULL, INDEX IDX_8ECEA5369D2D9088 (client_case_id), INDEX IDX_8ECEA536BE6CAE90 (mission_id), PRIMARY KEY(client_case_id, mission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_case_mission ADD CONSTRAINT FK_8ECEA5369D2D9088 FOREIGN KEY (client_case_id) REFERENCES client_case (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_case_mission ADD CONSTRAINT FK_8ECEA536BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_case ADD building_category_id INT NOT NULL');
        $this->addSql('ALTER TABLE client_case ADD CONSTRAINT FK_A9945A668FBAA222 FOREIGN KEY (building_category_id) REFERENCES building_category (id)');
        $this->addSql('CREATE INDEX IDX_A9945A668FBAA222 ON client_case (building_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_case_mission DROP FOREIGN KEY FK_8ECEA5369D2D9088');
        $this->addSql('ALTER TABLE client_case_mission DROP FOREIGN KEY FK_8ECEA536BE6CAE90');
        $this->addSql('DROP TABLE client_case_mission');
        $this->addSql('ALTER TABLE client_case DROP FOREIGN KEY FK_A9945A668FBAA222');
        $this->addSql('DROP INDEX IDX_A9945A668FBAA222 ON client_case');
        $this->addSql('ALTER TABLE client_case DROP building_category_id');
    }
}
