<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716120152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, date_of_birthday DATE NOT NULL, phone VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64967B3B43D');
        $this->addSql('DROP INDEX UNIQ_8D93D64967B3B43D ON user');
        $this->addSql('ALTER TABLE user ADD user_customer_id INT NOT NULL, CHANGE users_id user_chef_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494D0756FB FOREIGN KEY (user_chef_id) REFERENCES chef (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493A8E0A66 FOREIGN KEY (user_customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494D0756FB ON user (user_chef_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493A8E0A66 ON user (user_customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493A8E0A66');
        $this->addSql('DROP TABLE customer');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494D0756FB');
        $this->addSql('DROP INDEX UNIQ_8D93D6494D0756FB ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6493A8E0A66 ON user');
        $this->addSql('ALTER TABLE user ADD users_id INT NOT NULL, DROP user_chef_id, DROP user_customer_id');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64967B3B43D FOREIGN KEY (users_id) REFERENCES chef (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64967B3B43D ON user (users_id)');
    }
}
