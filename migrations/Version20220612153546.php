<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220612153546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phone_entry ADD owned_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE phone_entry ADD CONSTRAINT FK_D87BE86A5E70BCD7 FOREIGN KEY (owned_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D87BE86A5E70BCD7 ON phone_entry (owned_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE phone_entry DROP CONSTRAINT FK_D87BE86A5E70BCD7');
        $this->addSql('DROP INDEX IDX_D87BE86A5E70BCD7');
        $this->addSql('ALTER TABLE phone_entry DROP owned_by_id');
    }
}
