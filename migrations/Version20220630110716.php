<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220630110716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phone_entry DROP CONSTRAINT fk_d87be86a5e70bcd7');
        $this->addSql('DROP INDEX idx_d87be86a5e70bcd7');
        $this->addSql('ALTER TABLE phone_entry DROP owned_by_id');
    }

    public function down(Schema $schema): void
    {
        // После отката миграции необходимо в сущность PhoneEntry вернуть следующий код:
        //    #[ORM\ManyToOne(targetEntity: User::class)]
        //    #[ORM\JoinColumn(nullable: false)]
        //    private $owned_by;
        // И выполнить консольную команду app:return-owner, которая возвращает исходное значение в поле владельца записи
        $this->addSql('ALTER TABLE phone_entry ADD owned_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE phone_entry ADD CONSTRAINT fk_d87be86a5e70bcd7 FOREIGN KEY (owned_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d87be86a5e70bcd7 ON phone_entry (owned_by_id)');
    }
}
