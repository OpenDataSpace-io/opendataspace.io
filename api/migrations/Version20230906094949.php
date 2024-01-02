<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230906094949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, sub UUID NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649580282DC ON "user" (sub)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".sub IS \'(DC2Type:uuid)\'');
        // Migraiton for form and thing
        $this->addSql('CREATE SEQUENCE form_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE form (id INT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, date_created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_modified TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, jsonschema JSON DEFAULT NULL, uischema JSON DEFAULT NULL, form_data JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE thing (id UUID NOT NULL, name TEXT NOT NULL, date_created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_modified TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, properties JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN thing.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN thing.date_created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN thing.date_modified IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE "user"');

        // Migraiton for form and thing
        $this->addSql('DROP SEQUENCE form_id_seq CASCADE');
        $this->addSql('DROP TABLE form');
        $this->addSql('DROP TABLE thing');
    }
}
