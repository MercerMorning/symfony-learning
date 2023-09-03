<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230903201739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "order" (id BIGSERIAL NOT NULL, customer_id BIGINT DEFAULT NULL, executor_id BIGINT DEFAULT NULL, courier_id BIGINT DEFAULT NULL, description TEXT NOT NULL, status SMALLINT NOT NULL, price NUMERIC(5, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX order__customer_id__ind ON "order" (customer_id)');
        $this->addSql('CREATE INDEX order__executor_id__ind ON "order" (executor_id)');
        $this->addSql('CREATE INDEX order__delivery_man_id__ind ON "order" (courier_id)');
        $this->addSql('CREATE TABLE skill (id BIGSERIAL NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id BIGSERIAL NOT NULL, login VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, password VARCHAR(120) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_skill (user_id BIGINT NOT NULL, skill_id BIGINT NOT NULL, PRIMARY KEY(user_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_BCFF1F2FA76ED395 ON user_skill (user_id)');
        $this->addSql('CREATE INDEX IDX_BCFF1F2F5585C142 ON user_skill (skill_id)');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993988ABD09BB FOREIGN KEY (executor_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398E3D8151C FOREIGN KEY (courier_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_skill ADD CONSTRAINT FK_BCFF1F2FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_skill ADD CONSTRAINT FK_BCFF1F2F5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993989395C3F3');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993988ABD09BB');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398E3D8151C');
        $this->addSql('ALTER TABLE user_skill DROP CONSTRAINT FK_BCFF1F2FA76ED395');
        $this->addSql('ALTER TABLE user_skill DROP CONSTRAINT FK_BCFF1F2F5585C142');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_skill');
    }
}
