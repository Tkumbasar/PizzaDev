<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716121137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD user_customer_id INT NOT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E093A8E0A66 FOREIGN KEY (user_customer_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E093A8E0A66 ON customer (user_customer_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492C63A499');
        $this->addSql('DROP INDEX UNIQ_8D93D6492C63A499 ON user');
        $this->addSql('ALTER TABLE user DROP user_cutomer_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E093A8E0A66');
        $this->addSql('DROP INDEX UNIQ_81398E093A8E0A66 ON customer');
        $this->addSql('ALTER TABLE customer DROP user_customer_id');
        $this->addSql('ALTER TABLE user ADD user_cutomer_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492C63A499 FOREIGN KEY (user_cutomer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6492C63A499 ON user (user_cutomer_id)');
    }
}
