<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126002516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'users table creation';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(
            'CREATE TABLE `users` (
                  `id` varchar(36) NOT NULL,
                  `login` varchar (75) NOT NULL,
                  `password` varchar(50) NOT NULL,
                  `email` varchar(140) NOT NULL,
                  `status` enum(\'on\',\'off\',\'deleted\') DEFAULT \'off\' COMMENT \'On - user is active, off - inactive\',
                  `gender` enum(\'male\',\'female\',\'undefined\') DEFAULT \'undefined\',
                  `birthday` date,
                  `imageId` varchar(36) COMMENT \'User logo\',
                  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'Date of creation\',
                  `role` enum(\'user\',\'moderator\',\'admin\') DEFAULT \'user\' COMMENT \'Role of a user\',
                  UNIQUE KEY `UQ_Login` (`login`),
                  UNIQUE KEY `UQ_Email` (`email`),
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;'
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE `users`');
    }
}
