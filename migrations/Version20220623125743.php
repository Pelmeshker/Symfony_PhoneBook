<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220623125743 extends AbstractMigration
{

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        // this up() migration is auto-generated, please modify it to your needs
        // Для переноса данных на новую структуру после миграции ОБЯЗАТЕЛЬНО выполнить в консоли команду app:create-groups
        $this->addSql('CREATE SEQUENCE phone_groups_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE phone_groups (id INT NOT NULL, owned_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, priority INT NOT NULL, is_default BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DFC9A7F5E70BCD7 ON phone_groups (owned_by_id)');
        $this->addSql('CREATE TABLE phone_groups_phone_entry (phone_groups_id INT NOT NULL, phone_entry_id INT NOT NULL, PRIMARY KEY(phone_groups_id, phone_entry_id))');
        $this->addSql('CREATE INDEX IDX_109F668164936F7 ON phone_groups_phone_entry (phone_groups_id)');
        $this->addSql('CREATE INDEX IDX_109F668172715A4E ON phone_groups_phone_entry (phone_entry_id)');
        $this->addSql('ALTER TABLE phone_groups ADD CONSTRAINT FK_DFC9A7F5E70BCD7 FOREIGN KEY (owned_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phone_groups_phone_entry ADD CONSTRAINT FK_109F668164936F7 FOREIGN KEY (phone_groups_id) REFERENCES phone_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phone_groups_phone_entry ADD CONSTRAINT FK_109F668172715A4E FOREIGN KEY (phone_entry_id) REFERENCES phone_entry (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phone_entry ALTER COLUMN owned_by_id DROP NOT NULL;');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phone_groups_phone_entry DROP CONSTRAINT FK_109F668164936F7');
        $this->addSql('DROP SEQUENCE phone_groups_id_seq CASCADE');
        $this->addSql('DROP TABLE phone_groups');
        $this->addSql('DROP TABLE phone_groups_phone_entry');
        $this->addSql('ALTER TABLE phone_entry ALTER COLUMN owned_by_id SET NOT NULL;');
    }
}
