<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251204100001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE block (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, position INT DEFAULT 1 NOT NULL, page_id INT NOT NULL, type VARCHAR(32) NOT NULL, url VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, format VARCHAR(20) DEFAULT NULL, is_autoplay TINYINT(1) DEFAULT 0, INDEX IDX_831B9722C4663E4 (page_id), UNIQUE INDEX UNIQ_BLOCK_PAGE_POSITION (page_id, position), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(180) NOT NULL, slug VARCHAR(180) NOT NULL, full_slug VARCHAR(220) NOT NULL, is_homepage TINYINT(1) DEFAULT 0 NOT NULL, is_published TINYINT(1) DEFAULT 0 NOT NULL, is_in_main_nav TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, position INT DEFAULT 1 NOT NULL, parent_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_140AB6201D7A86A0 (full_slug), INDEX IDX_140AB620727ACA70 (parent_id), INDEX INDEX_PAGE_PARENT_SLUG (parent_id, slug), UNIQUE INDEX UNIQ_PAGE_PARENT_POSITION (parent_id, position), UNIQUE INDEX UNIQ_PAGE_SLUG (slug), UNIQUE INDEX UNIQ_PAGE_IS_HOMEPAGE (is_homepage), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B9722C4663E4 FOREIGN KEY (page_id) REFERENCES page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620727ACA70 FOREIGN KEY (parent_id) REFERENCES page (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B9722C4663E4');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620727ACA70');
        $this->addSql('DROP TABLE block');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
