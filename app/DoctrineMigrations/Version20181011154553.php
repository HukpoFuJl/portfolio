<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181011154553 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO `users` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`) VALUES (1, \'admin\', \'admin\', \'admin@admin.com\', \'admin@admin.com\', 1, NULL, \'$2y$13$ZJ24ihdjU/OFmPy3wJOuwOwcc4yQgX2Fl/OitTE3lDUzyQVzFlg/6\', \'2018-10-11 17:55:55\', NULL, NULL, \'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}\');');
        $this->addSql('INSERT INTO `user_groups` (`id`, `name`, `roles`, `parent_id`) VALUES (1, \'Master\', \'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}\', NULL);');
        $this->addSql('INSERT INTO `user_permissions` (`id`, `name`, `desc`) VALUES (1, \'admin_dashboard\', \'Acces to view admin dashboard\'); INSERT INTO `user_permissions` (`id`, `name`, `desc`) VALUES (2, \'admin_edit_users\', \'Edit users in admin dashboard\');');
        $this->addSql('INSERT INTO `users_to_user_groups` (`user_id`, `group_id`) VALUES (1, 1);');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
