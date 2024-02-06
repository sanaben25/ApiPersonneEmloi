<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240203151745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emploi (id INT AUTO_INCREMENT NOT NULL, nom_entreprise VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne_emploi (id INT AUTO_INCREMENT NOT NULL, personne_id INT DEFAULT NULL, emploi_id INT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, poste_occupe VARCHAR(255) NOT NULL, INDEX IDX_40DA8817A21BD112 (personne_id), INDEX IDX_40DA8817EC013E12 (emploi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE personne_emploi ADD CONSTRAINT FK_40DA8817A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE personne_emploi ADD CONSTRAINT FK_40DA8817EC013E12 FOREIGN KEY (emploi_id) REFERENCES emploi (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personne_emploi DROP FOREIGN KEY FK_40DA8817A21BD112');
        $this->addSql('ALTER TABLE personne_emploi DROP FOREIGN KEY FK_40DA8817EC013E12');
        $this->addSql('DROP TABLE emploi');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE personne_emploi');
    }
}
