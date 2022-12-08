<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207100742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE proprietaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, Prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietaire_chaton (proprietaire_id INT NOT NULL, chaton_id INT NOT NULL, INDEX IDX_2CFE18F776C50E4A (proprietaire_id), INDEX IDX_2CFE18F7640066C9 (chaton_id), PRIMARY KEY(proprietaire_id, chaton_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE proprietaire_chaton ADD CONSTRAINT FK_2CFE18F776C50E4A FOREIGN KEY (proprietaire_id) REFERENCES proprietaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proprietaire_chaton ADD CONSTRAINT FK_2CFE18F7640066C9 FOREIGN KEY (chaton_id) REFERENCES chaton (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proprietaire_chaton DROP FOREIGN KEY FK_2CFE18F776C50E4A');
        $this->addSql('ALTER TABLE proprietaire_chaton DROP FOREIGN KEY FK_2CFE18F7640066C9');
        $this->addSql('DROP TABLE proprietaire');
        $this->addSql('DROP TABLE proprietaire_chaton');
    }
}
