<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105105130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make columns nullable';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7166D1F9C');
        $this->addSql('ALTER TABLE event CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDB71F7E88B');
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDB166D1F9C');
        $this->addSql('ALTER TABLE volunteer CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDB71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDB166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDB71F7E88B');
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDB166D1F9C');
        $this->addSql('ALTER TABLE volunteer CHANGE project_id project_id INT NOT NULL');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDB71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDB166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7166D1F9C');
        $this->addSql('ALTER TABLE event CHANGE project_id project_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
    }
}
