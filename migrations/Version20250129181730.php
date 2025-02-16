<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129181730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE playlist_song (id INT AUTO_INCREMENT NOT NULL, playlists_id INT DEFAULT NULL, songs_id INT DEFAULT NULL, INDEX IDX_93F4D9C39F70CF56 (playlists_id), INDEX IDX_93F4D9C3C365A331 (songs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE playlist_song ADD CONSTRAINT FK_93F4D9C39F70CF56 FOREIGN KEY (playlists_id) REFERENCES playlist (id)');
        $this->addSql('ALTER TABLE playlist_song ADD CONSTRAINT FK_93F4D9C3C365A331 FOREIGN KEY (songs_id) REFERENCES song (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist_song DROP FOREIGN KEY FK_93F4D9C39F70CF56');
        $this->addSql('ALTER TABLE playlist_song DROP FOREIGN KEY FK_93F4D9C3C365A331');
        $this->addSql('DROP TABLE playlist_song');
    }
}
