<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200509210655 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tariff ADD flight_id INT NOT NULL');
        $this->addSql('ALTER TABLE tariff ADD CONSTRAINT FK_9465207D91F478C5 FOREIGN KEY (flight_id) REFERENCES flight (id)');
        $this->addSql('CREATE INDEX IDX_9465207D91F478C5 ON tariff (flight_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tariff DROP FOREIGN KEY FK_9465207D91F478C5');
        $this->addSql('DROP INDEX IDX_9465207D91F478C5 ON tariff');
        $this->addSql('ALTER TABLE tariff DROP flight_id');
    }
}
