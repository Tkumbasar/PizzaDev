<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716114710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, price INT NOT NULL, picture VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_menu (product_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_F0ED18324584665A (product_id), INDEX IDX_F0ED1832CCD7E912 (menu_id), PRIMARY KEY(product_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_menu ADD CONSTRAINT FK_F0ED18324584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_menu ADD CONSTRAINT FK_F0ED1832CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_menu DROP FOREIGN KEY FK_F0ED18324584665A');
        $this->addSql('ALTER TABLE product_menu DROP FOREIGN KEY FK_F0ED1832CCD7E912');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE product_menu');
    }
}
