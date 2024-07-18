<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716121320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chef ADD user_chef_id INT NOT NULL');
        $this->addSql('ALTER TABLE chef ADD CONSTRAINT FK_F24846E64D0756FB FOREIGN KEY (user_chef_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F24846E64D0756FB ON chef (user_chef_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chef DROP FOREIGN KEY FK_F24846E64D0756FB');
        $this->addSql('DROP INDEX UNIQ_F24846E64D0756FB ON chef');
        $this->addSql('ALTER TABLE chef DROP user_chef_id');
    }
}
