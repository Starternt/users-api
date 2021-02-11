<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210211112623 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'activation_links table creation';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(
            'CREATE TABLE `activation_links` (
                  `id` varchar(36) NOT NULL,
                  `token` varchar (75) NOT NULL,
                  `expire_at` datetime NOT NULL COMMENT \'Date and time when token expire\',
                  `activated` tinyint(1) NOT NULL DEFAULT 0 COMMENT \'Has token been activated or not\',
                  UNIQUE KEY `UQ_Token` (`token`),
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;'
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE `activation_links`');
    }
}
