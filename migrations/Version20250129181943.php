<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129181943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_playlist (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, playlists_id INT DEFAULT NULL, INDEX IDX_370FF52D67B3B43D (users_id), INDEX IDX_370FF52D9F70CF56 (playlists_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_playlist ADD CONSTRAINT FK_370FF52D67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_playlist ADD CONSTRAINT FK_370FF52D9F70CF56 FOREIGN KEY (playlists_id) REFERENCES playlist (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_playlist DROP FOREIGN KEY FK_370FF52D67B3B43D');
        $this->addSql('ALTER TABLE user_playlist DROP FOREIGN KEY FK_370FF52D9F70CF56');
        $this->addSql('DROP TABLE user_playlist');
    }
}
