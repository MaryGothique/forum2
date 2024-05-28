<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528063630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD articles_id INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C11EBAF6CC FOREIGN KEY (articles_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_64C19C11EBAF6CC ON category (articles_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C11EBAF6CC');
        $this->addSql('DROP INDEX IDX_64C19C11EBAF6CC ON category');
        $this->addSql('ALTER TABLE category DROP articles_id');
    }
}
