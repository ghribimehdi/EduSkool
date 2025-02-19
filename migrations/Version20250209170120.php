<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209170120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devoir (id INT AUTO_INCREMENT NOT NULL, enseignant_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, datelimite DATETIME NOT NULL, INDEX IDX_749EA771E455FCC0 (enseignant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enseignant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prénom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prénom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE soumission_devoir (id INT AUTO_INCREMENT NOT NULL, participant_id INT NOT NULL, devoir_id INT NOT NULL, contenu VARCHAR(255) NOT NULL, datesoumission DATETIME NOT NULL, note INT NOT NULL, INDEX IDX_816CEE289D1C3019 (participant_id), INDEX IDX_816CEE28C583534E (devoir_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devoir ADD CONSTRAINT FK_749EA771E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id)');
        $this->addSql('ALTER TABLE soumission_devoir ADD CONSTRAINT FK_816CEE289D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE soumission_devoir ADD CONSTRAINT FK_816CEE28C583534E FOREIGN KEY (devoir_id) REFERENCES devoir (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devoir DROP FOREIGN KEY FK_749EA771E455FCC0');
        $this->addSql('ALTER TABLE soumission_devoir DROP FOREIGN KEY FK_816CEE289D1C3019');
        $this->addSql('ALTER TABLE soumission_devoir DROP FOREIGN KEY FK_816CEE28C583534E');
        $this->addSql('DROP TABLE devoir');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE soumission_devoir');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
