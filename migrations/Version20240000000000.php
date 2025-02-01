<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240000000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migración inicial completa del proyecto';
    }

    public function up(Schema $schema): void
    {
        // Crear tablas base
        $this->addSql('CREATE TABLE style (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            description VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE profile (
            id INT AUTO_INCREMENT NOT NULL,
            photo VARCHAR(255) NOT NULL,
            description VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL,
            profile_id INT NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            birth_date DATE NOT NULL,
            UNIQUE INDEX UNIQ_8D93D649CCFA12B8 (profile_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE song (
            id INT AUTO_INCREMENT NOT NULL,
            genre_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            duration INT NOT NULL,
            album VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            replays INT NOT NULL,
            likes INT NOT NULL,
            INDEX IDX_33EDEEA14296D31F (genre_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE playlist (
            id INT AUTO_INCREMENT NOT NULL,
            owner_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            visibility VARCHAR(255) NOT NULL,
            replays INT NOT NULL,
            likes INT NOT NULL,
            INDEX IDX_D782112D7E3C61F9 (owner_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Crear tablas de relaciones
        $this->addSql('CREATE TABLE playlist_song (
            id INT AUTO_INCREMENT NOT NULL,
            playlists_id INT DEFAULT NULL,
            songs_id INT DEFAULT NULL,
            reproductions INT NOT NULL DEFAULT 0,
            INDEX IDX_93F4D9C39F70CF56 (playlists_id),
            INDEX IDX_93F4D9C3C365A331 (songs_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user_playlist (
            id INT AUTO_INCREMENT NOT NULL,
            users_id INT DEFAULT NULL,
            playlists_id INT DEFAULT NULL,
            reproductions INT NOT NULL DEFAULT 0,
            INDEX IDX_370FF52D67B3B43D (users_id),
            INDEX IDX_370FF52D9F70CF56 (playlists_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE profile_style (
            profile_id INT NOT NULL,
            style_id INT NOT NULL,
            INDEX IDX_C46927B8CCFA12B8 (profile_id),
            INDEX IDX_C46927B8BACD6074 (style_id),
            PRIMARY KEY(profile_id, style_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user_song (
            user_id INT NOT NULL,
            song_id INT NOT NULL,
            INDEX IDX_496CA268A76ED395 (user_id),
            INDEX IDX_496CA268A0BDB2F3 (song_id),
            PRIMARY KEY(user_id, song_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Añadir claves foráneas
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE song ADD CONSTRAINT FK_33EDEEA14296D31F FOREIGN KEY (genre_id) REFERENCES style (id)');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE playlist_song ADD CONSTRAINT FK_93F4D9C39F70CF56 FOREIGN KEY (playlists_id) REFERENCES playlist (id)');
        $this->addSql('ALTER TABLE playlist_song ADD CONSTRAINT FK_93F4D9C3C365A331 FOREIGN KEY (songs_id) REFERENCES song (id)');
        $this->addSql('ALTER TABLE user_playlist ADD CONSTRAINT FK_370FF52D67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_playlist ADD CONSTRAINT FK_370FF52D9F70CF56 FOREIGN KEY (playlists_id) REFERENCES playlist (id)');
        $this->addSql('ALTER TABLE profile_style ADD CONSTRAINT FK_C46927B8CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profile_style ADD CONSTRAINT FK_C46927B8BACD6074 FOREIGN KEY (style_id) REFERENCES style (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_song ADD CONSTRAINT FK_496CA268A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_song ADD CONSTRAINT FK_496CA268A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // Eliminar claves foráneas primero
        $this->addSql('ALTER TABLE user_song DROP FOREIGN KEY FK_496CA268A76ED395');
        $this->addSql('ALTER TABLE user_song DROP FOREIGN KEY FK_496CA268A0BDB2F3');
        $this->addSql('ALTER TABLE profile_style DROP FOREIGN KEY FK_C46927B8CCFA12B8');
        $this->addSql('ALTER TABLE profile_style DROP FOREIGN KEY FK_C46927B8BACD6074');
        $this->addSql('ALTER TABLE user_playlist DROP FOREIGN KEY FK_370FF52D67B3B43D');
        $this->addSql('ALTER TABLE user_playlist DROP FOREIGN KEY FK_370FF52D9F70CF56');
        $this->addSql('ALTER TABLE playlist_song DROP FOREIGN KEY FK_93F4D9C39F70CF56');
        $this->addSql('ALTER TABLE playlist_song DROP FOREIGN KEY FK_93F4D9C3C365A331');
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112D7E3C61F9');
        $this->addSql('ALTER TABLE song DROP FOREIGN KEY FK_33EDEEA14296D31F');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CCFA12B8');

        // Luego eliminar las tablas
        $this->addSql('DROP TABLE user_song');
        $this->addSql('DROP TABLE profile_style');
        $this->addSql('DROP TABLE user_playlist');
        $this->addSql('DROP TABLE playlist_song');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE song');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE style');
    }
} 