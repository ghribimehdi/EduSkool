<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212001151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devoir_participant (devoir_id INT NOT NULL, participant_id INT NOT NULL, INDEX IDX_9CA6913CC583534E (devoir_id), INDEX IDX_9CA6913C9D1C3019 (participant_id), PRIMARY KEY(devoir_id, participant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devoir_participant ADD CONSTRAINT FK_9CA6913CC583534E FOREIGN KEY (devoir_id) REFERENCES devoir (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devoir_participant ADD CONSTRAINT FK_9CA6913C9D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devoir_participant DROP FOREIGN KEY FK_9CA6913CC583534E');
        $this->addSql('ALTER TABLE devoir_participant DROP FOREIGN KEY FK_9CA6913C9D1C3019');
        $this->addSql('DROP TABLE devoir_participant');
    }
}
