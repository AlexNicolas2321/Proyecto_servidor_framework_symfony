<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129195349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profile_style (profile_id INT NOT NULL, style_id INT NOT NULL, INDEX IDX_C46927B8CCFA12B8 (profile_id), INDEX IDX_C46927B8BACD6074 (style_id), PRIMARY KEY(profile_id, style_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profile_style ADD CONSTRAINT FK_C46927B8CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profile_style ADD CONSTRAINT FK_C46927B8BACD6074 FOREIGN KEY (style_id) REFERENCES style (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile_style DROP FOREIGN KEY FK_C46927B8CCFA12B8');
        $this->addSql('ALTER TABLE profile_style DROP FOREIGN KEY FK_C46927B8BACD6074');
        $this->addSql('DROP TABLE profile_style');
    }
}
