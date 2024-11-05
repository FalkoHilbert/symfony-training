<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105091425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add organization_event table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization_event (organization_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_B529EC6032C8A3DE (organization_id), INDEX IDX_B529EC6071F7E88B (event_id), PRIMARY KEY(organization_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organization_event ADD CONSTRAINT FK_B529EC6032C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organization_event ADD CONSTRAINT FK_B529EC6071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_event DROP FOREIGN KEY FK_B529EC6032C8A3DE');
        $this->addSql('ALTER TABLE organization_event DROP FOREIGN KEY FK_B529EC6071F7E88B');
        $this->addSql('DROP TABLE organization_event');
    }
}
