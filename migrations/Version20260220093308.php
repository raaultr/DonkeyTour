<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260220093308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE despedida (tematica VARCHAR(30) NOT NULL, details TEXT DEFAULT NULL, place VARCHAR(30) NOT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE employee (social_security INT NOT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE therapy (place VARCHAR(30) NOT NULL, details TEXT DEFAULT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE tour (name VARCHAR(30) NOT NULL, itinerary TEXT NOT NULL, details TEXT DEFAULT NULL, stops INT NOT NULL, audio_explanation BOOLEAN NOT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE despedida ADD CONSTRAINT FK_309B3A05BF396750 FOREIGN KEY (id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1BF396750 FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE therapy ADD CONSTRAINT FK_BEFB2722BF396750 FOREIGN KEY (id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F969BF396750 FOREIGN KEY (id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service ADD type_service VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD type_user VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP CONSTRAINT FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C7440455BF396750');
        $this->addSql('ALTER TABLE despedida DROP CONSTRAINT FK_309B3A05BF396750');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1BF396750');
        $this->addSql('ALTER TABLE therapy DROP CONSTRAINT FK_BEFB2722BF396750');
        $this->addSql('ALTER TABLE tour DROP CONSTRAINT FK_6AD1F969BF396750');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE despedida');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE therapy');
        $this->addSql('DROP TABLE tour');
        $this->addSql('ALTER TABLE service DROP type_service');
        $this->addSql('ALTER TABLE "user" DROP type_user');
    }
}
