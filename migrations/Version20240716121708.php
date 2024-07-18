<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716121708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment VARCHAR(255) NOT NULL, INDEX IDX_9474526C9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_menu (comment_id INT NOT NULL, menu_id INT NOT NULL, INDEX IDX_5B33987FF8697D13 (comment_id), INDEX IDX_5B33987FCCD7E912 (menu_id), PRIMARY KEY(comment_id, menu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE comment_menu ADD CONSTRAINT FK_5B33987FF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_menu ADD CONSTRAINT FK_5B33987FCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9395C3F3');
        $this->addSql('ALTER TABLE comment_menu DROP FOREIGN KEY FK_5B33987FF8697D13');
        $this->addSql('ALTER TABLE comment_menu DROP FOREIGN KEY FK_5B33987FCCD7E912');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_menu');
    }
}
