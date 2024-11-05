<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105094855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relation between project and organization and add relation between event and project and volunteer and project';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_organization (project_id INT NOT NULL, organization_id INT NOT NULL, INDEX IDX_EB49871F166D1F9C (project_id), INDEX IDX_EB49871F32C8A3DE (organization_id), PRIMARY KEY(project_id, organization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_organization ADD CONSTRAINT FK_EB49871F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_organization ADD CONSTRAINT FK_EB49871F32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7166D1F9C ON event (project_id)');
        $this->addSql('ALTER TABLE volunteer ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDB166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5140DEDB166D1F9C ON volunteer (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_organization DROP FOREIGN KEY FK_EB49871F166D1F9C');
        $this->addSql('ALTER TABLE project_organization DROP FOREIGN KEY FK_EB49871F32C8A3DE');
        $this->addSql('DROP TABLE project_organization');
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDB166D1F9C');
        $this->addSql('DROP INDEX IDX_5140DEDB166D1F9C ON volunteer');
        $this->addSql('ALTER TABLE volunteer DROP project_id');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7166D1F9C');
        $this->addSql('DROP INDEX IDX_3BAE0AA7166D1F9C ON event');
        $this->addSql('ALTER TABLE event DROP project_id');
    }
}
