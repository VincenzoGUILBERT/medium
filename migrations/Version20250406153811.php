<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406153811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE comment_like (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, comment_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_8A55E25FA76ED395 (user_id), INDEX IDX_8A55E25FF8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE post_like (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, post_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_653627B8A76ED395 (user_id), INDEX IDX_653627B84B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_like ADD CONSTRAINT FK_653627B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_like ADD CONSTRAINT FK_653627B84B89032C FOREIGN KEY (post_id) REFERENCES post (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_like DROP FOREIGN KEY FK_8A55E25FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment_like DROP FOREIGN KEY FK_8A55E25FF8697D13
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_like DROP FOREIGN KEY FK_653627B8A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_like DROP FOREIGN KEY FK_653627B84B89032C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment_like
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE post_like
        SQL);
    }
}
