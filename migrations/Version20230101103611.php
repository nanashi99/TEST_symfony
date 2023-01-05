<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230101103611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, pays_id INT DEFAULT NULL, destinataire_id INT NOT NULL, civilite VARCHAR(50) NOT NULL, prenom VARCHAR(30) NOT NULL, nom VARCHAR(30) NOT NULL, email VARCHAR(150) NOT NULL, date_de_naissance DATE DEFAULT NULL, telephone VARCHAR(50) DEFAULT NULL, adresse VARCHAR(150) DEFAULT NULL, code_postal VARCHAR(10) DEFAULT NULL, ville VARCHAR(50) DEFAULT NULL, societe VARCHAR(150) DEFAULT NULL, message LONGTEXT NOT NULL, recevoire_newsletter TINYINT(1) DEFAULT NULL, date_denvoi DATE NOT NULL, ip_de_client VARCHAR(255) NOT NULL, INDEX IDX_4C62E638A6E44244 (pays_id), INDEX IDX_4C62E638A4F84F6E (destinataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE destinataire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE information_personnelles (id INT AUTO_INCREMENT NOT NULL, civilite VARCHAR(50) NOT NULL, prenom VARCHAR(30) NOT NULL, nom VARCHAR(30) NOT NULL, email VARCHAR(150) NOT NULL, date_de_naissance DATE DEFAULT NULL, telephone VARCHAR(50) DEFAULT NULL, adresse VARCHAR(150) DEFAULT NULL, code_postal VARCHAR(10) DEFAULT NULL, ville VARCHAR(50) DEFAULT NULL, pays VARCHAR(50) DEFAULT NULL, societe VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_contact (id INT AUTO_INCREMENT NOT NULL, destinataire VARCHAR(150) NOT NULL, message LONGTEXT NOT NULL, recevoire_newsletter TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, pays VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES destinataire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638A6E44244');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638A4F84F6E');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE destinataire');
        $this->addSql('DROP TABLE information_personnelles');
        $this->addSql('DROP TABLE message_contact');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
