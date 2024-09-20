<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240920124255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE fullname fullname VARCHAR(255) DEFAULT NULL, CHANGE birthdate birthdate DATE DEFAULT NULL, CHANGE street street VARCHAR(255) DEFAULT NULL, CHANGE number number VARCHAR(10) DEFAULT NULL, CHANGE zip_code zip_code VARCHAR(15) DEFAULT NULL, CHANGE city city VARCHAR(100) DEFAULT NULL, CHANGE state state VARCHAR(100) DEFAULT NULL, CHANGE phone phone VARCHAR(15) DEFAULT NULL, CHANGE mobile mobile VARCHAR(15) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE fullname fullname VARCHAR(255) NOT NULL, CHANGE birthdate birthdate DATE NOT NULL, CHANGE street street VARCHAR(255) NOT NULL, CHANGE number number VARCHAR(10) NOT NULL, CHANGE zip_code zip_code VARCHAR(15) NOT NULL, CHANGE city city VARCHAR(100) NOT NULL, CHANGE state state VARCHAR(100) NOT NULL, CHANGE phone phone VARCHAR(15) NOT NULL, CHANGE mobile mobile VARCHAR(15) NOT NULL');
    }
}
