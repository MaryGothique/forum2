<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528071014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE article CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1B03A8386');
        $this->addSql('DROP INDEX IDX_64C19C1B03A8386 ON category');
        $this->addSql('ALTER TABLE category ADD name VARCHAR(100) NOT NULL, DROP created_by_id, DROP title, DROP author');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE article CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE category ADD created_by_id INT NOT NULL, ADD title VARCHAR(50) NOT NULL, ADD author VARCHAR(255) DEFAULT NULL, DROP name');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_64C19C1B03A8386 ON category (created_by_id)');
    }
}
