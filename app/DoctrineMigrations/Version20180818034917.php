<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180818034917 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE is_scientist is_scientist TINYINT(1) DEFAULT NULL, CHANGE first_name first_name VARCHAR(255) DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE university_name university_name VARCHAR(255) DEFAULT NULL, CHANGE avatar_uri avatar_uri VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE is_scientist is_scientist TINYINT(1) NOT NULL, CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE university_name university_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE avatar_uri avatar_uri VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
