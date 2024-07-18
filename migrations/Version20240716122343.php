<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716122343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', total_price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_menu (order_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_30F400848D9F6D38 (order_id), INDEX IDX_30F40084CCD7E912 (menu_id), PRIMARY KEY(order_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_customer (order_id INT NOT NULL, customer_id INT NOT NULL, INDEX IDX_60C16CB88D9F6D38 (order_id), INDEX IDX_60C16CB89395C3F3 (customer_id), PRIMARY KEY(order_id, customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F400848D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_menu ADD CONSTRAINT FK_30F40084CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_customer ADD CONSTRAINT FK_60C16CB88D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_customer ADD CONSTRAINT FK_60C16CB89395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F400848D9F6D38');
        $this->addSql('ALTER TABLE order_menu DROP FOREIGN KEY FK_30F40084CCD7E912');
        $this->addSql('ALTER TABLE order_customer DROP FOREIGN KEY FK_60C16CB88D9F6D38');
        $this->addSql('ALTER TABLE order_customer DROP FOREIGN KEY FK_60C16CB89395C3F3');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_menu');
        $this->addSql('DROP TABLE order_customer');
    }
}
