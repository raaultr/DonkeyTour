<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260302113216 extends AbstractMigration
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
        $this->addSql('CREATE TABLE employee (social_security VARCHAR(20) NOT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE sponsorship (details TEXT DEFAULT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE therapy (place VARCHAR(30) NOT NULL, details TEXT DEFAULT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE tour (name VARCHAR(30) NOT NULL, itinerary TEXT NOT NULL, details TEXT DEFAULT NULL, stops INT NOT NULL, audio_explanation BOOLEAN NOT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1BF396750 FOREIGN KEY (id) REFERENCES "user" (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sponsorship ADD CONSTRAINT FK_C0F10CD4BF396750 FOREIGN KEY (id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE therapy ADD CONSTRAINT FK_BEFB2722BF396750 FOREIGN KEY (id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F969BF396750 FOREIGN KEY (id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_reserve ADD reserve_who BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE client_reserve ADD client_assist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client_reserve ADD CONSTRAINT FK_4DDDAE5F55BAE2E7 FOREIGN KEY (client_assist_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_4DDDAE5F55BAE2E7 ON client_reserve (client_assist_id)');
        $this->addSql('ALTER TABLE donkey ALTER reserve_id DROP NOT NULL');
        $this->addSql('ALTER TABLE reserve ADD employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reserve ADD booked_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reserve ADD selected_donkey_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reserve ALTER reserve_date DROP NOT NULL');
        $this->addSql('ALTER TABLE reserve ALTER pay_id DROP NOT NULL');
        $this->addSql('ALTER TABLE reserve ADD CONSTRAINT FK_1FE0EA228C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE reserve ADD CONSTRAINT FK_1FE0EA22F4A5BD90 FOREIGN KEY (booked_by_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE reserve ADD CONSTRAINT FK_1FE0EA22D16DA6D2 FOREIGN KEY (selected_donkey_id) REFERENCES donkey (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_1FE0EA228C03F15C ON reserve (employee_id)');
        $this->addSql('CREATE INDEX IDX_1FE0EA22F4A5BD90 ON reserve (booked_by_id)');
        $this->addSql('CREATE INDEX IDX_1FE0EA22D16DA6D2 ON reserve (selected_donkey_id)');
        $this->addSql('ALTER TABLE "user" ADD type_user VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP CONSTRAINT FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C7440455BF396750');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1BF396750');
        $this->addSql('ALTER TABLE sponsorship DROP CONSTRAINT FK_C0F10CD4BF396750');
        $this->addSql('ALTER TABLE therapy DROP CONSTRAINT FK_BEFB2722BF396750');
        $this->addSql('ALTER TABLE tour DROP CONSTRAINT FK_6AD1F969BF396750');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE sponsorship');
        $this->addSql('DROP TABLE therapy');
        $this->addSql('DROP TABLE tour');
        $this->addSql('ALTER TABLE client_reserve DROP CONSTRAINT FK_4DDDAE5F55BAE2E7');
        $this->addSql('DROP INDEX IDX_4DDDAE5F55BAE2E7');
        $this->addSql('ALTER TABLE client_reserve DROP reserve_who');
        $this->addSql('ALTER TABLE client_reserve DROP client_assist_id');
        $this->addSql('ALTER TABLE donkey ALTER reserve_id SET NOT NULL');
        $this->addSql('ALTER TABLE reserve DROP CONSTRAINT FK_1FE0EA228C03F15C');
        $this->addSql('ALTER TABLE reserve DROP CONSTRAINT FK_1FE0EA22F4A5BD90');
        $this->addSql('ALTER TABLE reserve DROP CONSTRAINT FK_1FE0EA22D16DA6D2');
        $this->addSql('DROP INDEX IDX_1FE0EA228C03F15C');
        $this->addSql('DROP INDEX IDX_1FE0EA22F4A5BD90');
        $this->addSql('DROP INDEX IDX_1FE0EA22D16DA6D2');
        $this->addSql('ALTER TABLE reserve DROP employee_id');
        $this->addSql('ALTER TABLE reserve DROP booked_by_id');
        $this->addSql('ALTER TABLE reserve DROP selected_donkey_id');
        $this->addSql('ALTER TABLE reserve ALTER reserve_date SET NOT NULL');
        $this->addSql('ALTER TABLE reserve ALTER pay_id SET NOT NULL');
        $this->addSql('ALTER TABLE "user" DROP type_user');
    }
}
