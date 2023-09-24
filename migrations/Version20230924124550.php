<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230924124550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE measurements DROP CONSTRAINT fk_71920f21cffe9ad6');
        $this->addSql('DROP INDEX idx_71920f21cffe9ad6');
        $this->addSql('ALTER TABLE measurements DROP orders_id');
        $this->addSql('ALTER TABLE "order" ADD measurement_id INT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN "order".measurement_id IS \'Measurement id\'');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398924EA134 FOREIGN KEY (measurement_id) REFERENCES measurements (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F5299398924EA134 ON "order" (measurement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE measurements ADD orders_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE measurements ADD CONSTRAINT fk_71920f21cffe9ad6 FOREIGN KEY (orders_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_71920f21cffe9ad6 ON measurements (orders_id)');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398924EA134');
        $this->addSql('DROP INDEX IDX_F5299398924EA134');
        $this->addSql('ALTER TABLE "order" DROP measurement_id');
    }
}
