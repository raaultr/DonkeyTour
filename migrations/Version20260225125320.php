<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225125320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee ALTER social_security TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE reserve ADD booked_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reserve ADD selected_donkey_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reserve ALTER pay_id DROP NOT NULL');
        $this->addSql('ALTER TABLE reserve ALTER employee_id DROP NOT NULL');
        $this->addSql('ALTER TABLE reserve ADD CONSTRAINT FK_1FE0EA22F4A5BD90 FOREIGN KEY (booked_by_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE reserve ADD CONSTRAINT FK_1FE0EA22D16DA6D2 FOREIGN KEY (selected_donkey_id) REFERENCES donkey (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_1FE0EA22F4A5BD90 ON reserve (booked_by_id)');
        $this->addSql('CREATE INDEX IDX_1FE0EA22D16DA6D2 ON reserve (selected_donkey_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee ALTER social_security TYPE INT');
        $this->addSql('ALTER TABLE reserve DROP CONSTRAINT FK_1FE0EA22F4A5BD90');
        $this->addSql('ALTER TABLE reserve DROP CONSTRAINT FK_1FE0EA22D16DA6D2');
        $this->addSql('DROP INDEX IDX_1FE0EA22F4A5BD90');
        $this->addSql('DROP INDEX IDX_1FE0EA22D16DA6D2');
        $this->addSql('ALTER TABLE reserve DROP booked_by_id');
        $this->addSql('ALTER TABLE reserve DROP selected_donkey_id');
        $this->addSql('ALTER TABLE reserve ALTER pay_id SET NOT NULL');
        $this->addSql('ALTER TABLE reserve ALTER employee_id SET NOT NULL');
    }
}
