<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716120938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493A8E0A66');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494D0756FB');
        $this->addSql('DROP INDEX UNIQ_8D93D6494D0756FB ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6493A8E0A66 ON user');
        $this->addSql('ALTER TABLE user ADD user_cutomer_id INT NOT NULL, DROP user_chef_id, DROP user_customer_id');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492C63A499 FOREIGN KEY (user_cutomer_id) REFERENCES customer (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6492C63A499 ON user (user_cutomer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492C63A499');
        $this->addSql('DROP INDEX UNIQ_8D93D6492C63A499 ON user');
        $this->addSql('ALTER TABLE user ADD user_customer_id INT NOT NULL, CHANGE user_cutomer_id user_chef_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493A8E0A66 FOREIGN KEY (user_customer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494D0756FB FOREIGN KEY (user_chef_id) REFERENCES chef (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494D0756FB ON user (user_chef_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493A8E0A66 ON user (user_customer_id)');
    }
}
