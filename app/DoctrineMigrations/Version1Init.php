<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version1Init extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_groups (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_953F224D5E237E06 (name), INDEX IDX_953F224D727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_groups_to_user_permissions (group_id INT NOT NULL, perm_id INT NOT NULL, INDEX IDX_CFCE8987FE54D947 (group_id), INDEX IDX_CFCE8987FA6311EF (perm_id), PRIMARY KEY(group_id, perm_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_permissions (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, `desc` VARCHAR(255) NOT NULL, INDEX IDX_84F605FA727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_1483A5E992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_1483A5E9A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_1483A5E9C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_to_user_groups (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_C514859BA76ED395 (user_id), INDEX IDX_C514859BFE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_groups ADD CONSTRAINT FK_953F224D727ACA70 FOREIGN KEY (parent_id) REFERENCES user_groups (id)');
        $this->addSql('ALTER TABLE user_groups_to_user_permissions ADD CONSTRAINT FK_CFCE8987FE54D947 FOREIGN KEY (group_id) REFERENCES user_groups (id)');
        $this->addSql('ALTER TABLE user_groups_to_user_permissions ADD CONSTRAINT FK_CFCE8987FA6311EF FOREIGN KEY (perm_id) REFERENCES user_permissions (id)');
        $this->addSql('ALTER TABLE user_permissions ADD CONSTRAINT FK_84F605FA727ACA70 FOREIGN KEY (parent_id) REFERENCES user_groups (id)');
        $this->addSql('ALTER TABLE users_to_user_groups ADD CONSTRAINT FK_C514859BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_to_user_groups ADD CONSTRAINT FK_C514859BFE54D947 FOREIGN KEY (group_id) REFERENCES user_groups (id)');
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, author_id INT NOT NULL, image VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, updateDate DATETIME NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_to_categories (blog_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_6E466E79DAE07E97 (blog_id), INDEX IDX_6E466E7912469DE2 (category_id), PRIMARY KEY(blog_id, category_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_categories (id INT AUTO_INCREMENT NOT NULL, category_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_to_categories ADD CONSTRAINT FK_6E466E79DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE blog_to_categories ADD CONSTRAINT FK_6E466E7912469DE2 FOREIGN KEY (category_id) REFERENCES blog_categories (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE blog_to_categories');
        $this->addSql('DROP TABLE blog_categories');
        $this->addSql('ALTER TABLE user_groups DROP FOREIGN KEY FK_953F224D727ACA70');
        $this->addSql('ALTER TABLE user_groups_to_user_permissions DROP FOREIGN KEY FK_CFCE8987FE54D947');
        $this->addSql('ALTER TABLE user_permissions DROP FOREIGN KEY FK_84F605FA727ACA70');
        $this->addSql('ALTER TABLE users_to_user_groups DROP FOREIGN KEY FK_C514859BFE54D947');
        $this->addSql('ALTER TABLE user_groups_to_user_permissions DROP FOREIGN KEY FK_CFCE8987FA6311EF');
        $this->addSql('ALTER TABLE users_to_user_groups DROP FOREIGN KEY FK_C514859BA76ED395')->addSql('DROP TABLE user_groups');
        $this->addSql('DROP TABLE user_groups_to_user_permissions');
        $this->addSql('DROP TABLE user_permissions');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_to_user_groups');
    }
}
