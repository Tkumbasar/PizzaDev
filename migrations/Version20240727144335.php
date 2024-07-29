<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240727144335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_comment DROP FOREIGN KEY FK_1CAF4FD2CCD7E912');
        $this->addSql('ALTER TABLE menu_comment DROP FOREIGN KEY FK_1CAF4FD2F8697D13');
        $this->addSql('DROP TABLE menu_comment');
        $this->addSql('ALTER TABLE comment ADD customer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_9474526C9395C3F3 ON comment (customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_comment (menu_id INT NOT NULL, comment_id INT NOT NULL, INDEX IDX_1CAF4FD2CCD7E912 (menu_id), INDEX IDX_1CAF4FD2F8697D13 (comment_id), PRIMARY KEY(menu_id, comment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE menu_comment ADD CONSTRAINT FK_1CAF4FD2CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_comment ADD CONSTRAINT FK_1CAF4FD2F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9395C3F3');
        $this->addSql('DROP INDEX IDX_9474526C9395C3F3 ON comment');
        $this->addSql('ALTER TABLE comment DROP customer_id');
    }
}
