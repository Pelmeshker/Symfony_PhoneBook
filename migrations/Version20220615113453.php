<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615113453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE phone_entry (id INT NOT NULL, owned_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, number INT NOT NULL, description VARCHAR(255) DEFAULT NULL, priority INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D87BE86A5E70BCD7 ON phone_entry (owned_by_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, is_banned BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('ALTER TABLE phone_entry ADD CONSTRAINT FK_D87BE86A5E70BCD7 FOREIGN KEY (owned_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE phone_entry DROP CONSTRAINT FK_D87BE86A5E70BCD7');
        $this->addSql('DROP TABLE phone_entry');
        $this->addSql('DROP TABLE "user"');
    }
}
