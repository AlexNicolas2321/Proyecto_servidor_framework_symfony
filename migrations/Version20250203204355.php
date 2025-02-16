<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203204355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist CHANGE owner_id owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE playlist_song DROP FOREIGN KEY FK_93F4D9C39F70CF56');
        $this->addSql('ALTER TABLE playlist_song DROP FOREIGN KEY FK_93F4D9C3C365A331');
        $this->addSql('DROP INDEX IDX_93F4D9C3C365A331 ON playlist_song');
        $this->addSql('DROP INDEX IDX_93F4D9C39F70CF56 ON playlist_song');
        $this->addSql('ALTER TABLE playlist_song ADD playlist_id INT NOT NULL, ADD song_id INT NOT NULL, ADD reproductions INT NOT NULL, DROP playlists_id, DROP songs_id');
        $this->addSql('ALTER TABLE playlist_song ADD CONSTRAINT FK_93F4D9C36BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id)');
        $this->addSql('ALTER TABLE playlist_song ADD CONSTRAINT FK_93F4D9C3A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id)');
        $this->addSql('CREATE INDEX IDX_93F4D9C36BBD148 ON playlist_song (playlist_id)');
        $this->addSql('CREATE INDEX IDX_93F4D9C3A0BDB2F3 ON playlist_song (song_id)');
        $this->addSql('ALTER TABLE profile CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE song CHANGE genre_id genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE profile_id profile_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_playlist ADD reproductions INT NOT NULL, CHANGE users_id users_id INT NOT NULL, CHANGE playlists_id playlists_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist CHANGE owner_id owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_playlist DROP reproductions, CHANGE users_id users_id INT DEFAULT NULL, CHANGE playlists_id playlists_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE profile_id profile_id INT NOT NULL');
        $this->addSql('ALTER TABLE playlist_song DROP FOREIGN KEY FK_93F4D9C36BBD148');
        $this->addSql('ALTER TABLE playlist_song DROP FOREIGN KEY FK_93F4D9C3A0BDB2F3');
        $this->addSql('DROP INDEX IDX_93F4D9C36BBD148 ON playlist_song');
        $this->addSql('DROP INDEX IDX_93F4D9C3A0BDB2F3 ON playlist_song');
        $this->addSql('ALTER TABLE playlist_song ADD playlists_id INT DEFAULT NULL, ADD songs_id INT DEFAULT NULL, DROP playlist_id, DROP song_id, DROP reproductions');
        $this->addSql('ALTER TABLE playlist_song ADD CONSTRAINT FK_93F4D9C39F70CF56 FOREIGN KEY (playlists_id) REFERENCES playlist (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE playlist_song ADD CONSTRAINT FK_93F4D9C3C365A331 FOREIGN KEY (songs_id) REFERENCES song (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_93F4D9C3C365A331 ON playlist_song (songs_id)');
        $this->addSql('CREATE INDEX IDX_93F4D9C39F70CF56 ON playlist_song (playlists_id)');
        $this->addSql('ALTER TABLE profile CHANGE photo photo VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE song CHANGE genre_id genre_id INT NOT NULL');
    }
}
