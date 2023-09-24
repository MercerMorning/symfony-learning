<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230924124316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE measurements (id SERIAL NOT NULL, orders_id BIGINT DEFAULT NULL, title VARCHAR(255) NOT NULL, abbreviation VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_71920F21CFFE9AD6 ON measurements (orders_id)');
        $this->addSql('COMMENT ON COLUMN measurements.id IS \'Measurement id\'');
        $this->addSql('ALTER TABLE measurements ADD CONSTRAINT FK_71920F21CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE measurements DROP CONSTRAINT FK_71920F21CFFE9AD6');
        $this->addSql('DROP TABLE measurements');
    }
}
