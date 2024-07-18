<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716115318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chef (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, date_of_birthday DATE NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D64967B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64967B3B43D FOREIGN KEY (users_id) REFERENCES chef (id)');
        $this->addSql('ALTER TABLE menu ADD chef_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93150A48F1 FOREIGN KEY (chef_id) REFERENCES chef (id)');
        $this->addSql('CREATE INDEX IDX_7D053A93150A48F1 ON menu (chef_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93150A48F1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64967B3B43D');
        $this->addSql('DROP TABLE chef');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_7D053A93150A48F1 ON menu');
        $this->addSql('ALTER TABLE menu DROP chef_id');
    }
}
