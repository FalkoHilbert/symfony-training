<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106103532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization_volunteer (organization_id INT NOT NULL, volunteer_id INT NOT NULL, INDEX IDX_697704DD32C8A3DE (organization_id), INDEX IDX_697704DD8EFAB6B1 (volunteer_id), PRIMARY KEY(organization_id, volunteer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organization_volunteer ADD CONSTRAINT FK_697704DD32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organization_volunteer ADD CONSTRAINT FK_697704DD8EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volunteer ADD for_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDB9B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5140DEDB9B5BB4B8 ON volunteer (for_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_volunteer DROP FOREIGN KEY FK_697704DD32C8A3DE');
        $this->addSql('ALTER TABLE organization_volunteer DROP FOREIGN KEY FK_697704DD8EFAB6B1');
        $this->addSql('DROP TABLE organization_volunteer');
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDB9B5BB4B8');
        $this->addSql('DROP INDEX IDX_5140DEDB9B5BB4B8 ON volunteer');
        $this->addSql('ALTER TABLE volunteer DROP for_user_id');
    }
}
