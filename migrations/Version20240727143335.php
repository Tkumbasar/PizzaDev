<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240727143335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_menu (comment_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_5B33987FF8697D13 (comment_id), INDEX IDX_5B33987FCCD7E912 (menu_id), PRIMARY KEY(comment_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_comment (menu_id INT NOT NULL, comment_id INT NOT NULL, INDEX IDX_1CAF4FD2CCD7E912 (menu_id), INDEX IDX_1CAF4FD2F8697D13 (comment_id), PRIMARY KEY(menu_id, comment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_menu ADD CONSTRAINT FK_5B33987FF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_menu ADD CONSTRAINT FK_5B33987FCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_comment ADD CONSTRAINT FK_1CAF4FD2CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_comment ADD CONSTRAINT FK_1CAF4FD2F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9395C3F3');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CCCD7E912');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CD00A4DAC');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CD9C4C9C1');
        $this->addSql('DROP INDEX IDX_9474526CCCD7E912 ON comment');
        $this->addSql('DROP INDEX IDX_9474526CD00A4DAC ON comment');
        $this->addSql('DROP INDEX IDX_9474526CD9C4C9C1 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C9395C3F3 ON comment');
        $this->addSql('ALTER TABLE comment DROP customer_id, DROP menu_id, DROP customer_menu_id, DROP menu_comment_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_menu DROP FOREIGN KEY FK_5B33987FF8697D13');
        $this->addSql('ALTER TABLE comment_menu DROP FOREIGN KEY FK_5B33987FCCD7E912');
        $this->addSql('ALTER TABLE menu_comment DROP FOREIGN KEY FK_1CAF4FD2CCD7E912');
        $this->addSql('ALTER TABLE menu_comment DROP FOREIGN KEY FK_1CAF4FD2F8697D13');
        $this->addSql('DROP TABLE comment_menu');
        $this->addSql('DROP TABLE menu_comment');
        $this->addSql('ALTER TABLE comment ADD customer_id INT DEFAULT NULL, ADD menu_id INT DEFAULT NULL, ADD customer_menu_id INT DEFAULT NULL, ADD menu_comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD00A4DAC FOREIGN KEY (customer_menu_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD9C4C9C1 FOREIGN KEY (menu_comment_id) REFERENCES menu (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9474526CCCD7E912 ON comment (menu_id)');
        $this->addSql('CREATE INDEX IDX_9474526CD00A4DAC ON comment (customer_menu_id)');
        $this->addSql('CREATE INDEX IDX_9474526CD9C4C9C1 ON comment (menu_comment_id)');
        $this->addSql('CREATE INDEX IDX_9474526C9395C3F3 ON comment (customer_id)');
    }
}
