<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230618164912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_f52993989395c3f3');
        $this->addSql('DROP INDEX order__customer_id__ind');
        $this->addSql('CREATE INDEX order__executor_id__ind ON "order" (executor_id)');
        $this->addSql('CREATE INDEX order__customer_id__ind ON "order" (customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX order__executor_id__ind');
        $this->addSql('DROP INDEX order__customer_id__ind');
        $this->addSql('CREATE INDEX idx_f52993989395c3f3 ON "order" (customer_id)');
        $this->addSql('CREATE INDEX order__customer_id__ind ON "order" (executor_id)');
    }
}
