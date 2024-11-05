<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105091112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add event_id to volunteer';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDB71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5140DEDB71F7E88B ON volunteer (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDB71F7E88B');
        $this->addSql('DROP INDEX IDX_5140DEDB71F7E88B ON volunteer');
        $this->addSql('ALTER TABLE volunteer DROP event_id');
    }
}
