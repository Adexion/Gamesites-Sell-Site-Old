<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202183235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_promotion DROP CONSTRAINT fk_c1fdf035139df194');
        $this->addSql('ALTER TABLE developer_module DROP CONSTRAINT fk_f9c46abf64dd9267');
        $this->addSql('ALTER TABLE production_history DROP CONSTRAINT fk_32affd96afc2b591');
        $this->addSql('ALTER TABLE subscription_module DROP CONSTRAINT fk_c5d23eafafc2b591');
        $this->addSql('ALTER TABLE application_module_history DROP CONSTRAINT fk_eb0e1062afc2b591');
        $this->addSql('ALTER TABLE developer_module DROP CONSTRAINT fk_f9c46abfafc2b591');
        $this->addSql('ALTER TABLE production_module DROP CONSTRAINT fk_c384b275afc2b591');
        $this->addSql('ALTER TABLE developer DROP CONSTRAINT fk_65fb8b9a2693382e');
        $this->addSql('ALTER TABLE application_subscription DROP CONSTRAINT fk_d3af8a3e030acd');
        $this->addSql('ALTER TABLE application_module_history DROP CONSTRAINT fk_eb0e10623e030acd');
        $this->addSql('ALTER TABLE production DROP CONSTRAINT fk_d3edb1e02693382e');
        $this->addSql('ALTER TABLE production_history DROP CONSTRAINT fk_32affd96ecc6147f');
        $this->addSql('ALTER TABLE production_module DROP CONSTRAINT fk_c384b275ecc6147f');
        $this->addSql('ALTER TABLE subscription_module DROP CONSTRAINT fk_c5d23eaf7acd6ec2');
        $this->addSql('ALTER TABLE application_subscription DROP CONSTRAINT fk_d3af8a9a1887dc');
        $this->addSql('DROP SEQUENCE application_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE developer_application_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE production_application_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE module_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE production_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE developer_application_uuid_seq CASCADE');
        $this->addSql('DROP SEQUENCE production_application_uuid_seq CASCADE');
        $this->addSql('DROP SEQUENCE application_module_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_promotion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE subscription_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE promotion_id_seq CASCADE');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE user_promotion');
        $this->addSql('DROP TABLE payment_history');
        $this->addSql('DROP TABLE production_history');
        $this->addSql('DROP TABLE developer');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE subscription_module');
        $this->addSql('DROP TABLE application_subscription');
        $this->addSql('DROP TABLE application_module_history');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE developer_module');
        $this->addSql('DROP TABLE production');
        $this->addSql('DROP TABLE production_module');
        $this->addSql('DROP TABLE subscription');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE application_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE developer_application_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE production_application_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE module_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE production_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE developer_application_uuid_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE production_application_uuid_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE application_module_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_promotion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE subscription_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE promotion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE promotion (id INT NOT NULL, user_id INT DEFAULT NULL, value VARCHAR(25) NOT NULL, percentage SMALLINT NOT NULL, use INT DEFAULT 1 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_c11d7dd1a76ed395 ON promotion (user_id)');
        $this->addSql('CREATE TABLE user_promotion (id INT NOT NULL, promotion_id INT DEFAULT NULL, user_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_c1fdf035a76ed395 ON user_promotion (user_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_c1fdf035139df194 ON user_promotion (promotion_id)');
        $this->addSql('CREATE TABLE payment_history (id INT NOT NULL, user_id INT DEFAULT NULL, payment_id VARCHAR(255) NOT NULL, type INT NOT NULL, price NUMERIC(10, 0) NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_3ef37ea1a76ed395 ON payment_history (user_id)');
        $this->addSql('CREATE TABLE production_history (id INT NOT NULL, production_id UUID DEFAULT NULL, module_id INT DEFAULT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_32affd96afc2b591 ON production_history (module_id)');
        $this->addSql('CREATE INDEX idx_32affd96ecc6147f ON production_history (production_id)');
        $this->addSql('COMMENT ON COLUMN production_history.production_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE developer (uuid UUID NOT NULL, application_uuid UUID DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX uniq_65fb8b9a2693382e ON developer (application_uuid)');
        $this->addSql('COMMENT ON COLUMN developer.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN developer.application_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE module (id INT NOT NULL, name TEXT NOT NULL, description TEXT NOT NULL, app_type SMALLINT NOT NULL, cost NUMERIC(10, 0) NOT NULL, percentage SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_c2426286de44026 ON module (description)');
        $this->addSql('CREATE UNIQUE INDEX uniq_c2426285e237e06 ON module (name)');
        $this->addSql('CREATE TABLE subscription_module (subscription_in INT NOT NULL, module_id INT NOT NULL, PRIMARY KEY(subscription_in, module_id))');
        $this->addSql('CREATE INDEX idx_c5d23eafafc2b591 ON subscription_module (module_id)');
        $this->addSql('CREATE INDEX idx_c5d23eaf7acd6ec2 ON subscription_module (subscription_in)');
        $this->addSql('CREATE TABLE application_subscription (id INT NOT NULL, application_id UUID NOT NULL, subscription_id INT NOT NULL, create_by_id INT NOT NULL, cost NUMERIC(10, 0) NOT NULL, start_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expiry_date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_d3af8a9a1887dc ON application_subscription (subscription_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_d3af8a9e085865 ON application_subscription (create_by_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_d3af8a3e030acd ON application_subscription (application_id)');
        $this->addSql('COMMENT ON COLUMN application_subscription.application_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE application_module_history (id INT NOT NULL, application_id UUID DEFAULT NULL, module_id INT DEFAULT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_eb0e10623e030acd ON application_module_history (application_id)');
        $this->addSql('CREATE INDEX idx_eb0e1062afc2b591 ON application_module_history (module_id)');
        $this->addSql('COMMENT ON COLUMN application_module_history.application_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE application (uuid UUID NOT NULL, owner_id INT NOT NULL, name TEXT NOT NULL, icon BYTEA DEFAULT NULL, app_type SMALLINT NOT NULL, contact_email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX uniq_a45bddc17e3c61f9 ON application (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_a45bddc15e237e06 ON application (name)');
        $this->addSql('COMMENT ON COLUMN application.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE developer_module (developer_id UUID NOT NULL, module_id INT NOT NULL, PRIMARY KEY(developer_id, module_id))');
        $this->addSql('CREATE INDEX idx_f9c46abf64dd9267 ON developer_module (developer_id)');
        $this->addSql('CREATE INDEX idx_f9c46abfafc2b591 ON developer_module (module_id)');
        $this->addSql('COMMENT ON COLUMN developer_module.developer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE production (uuid UUID NOT NULL, application_uuid UUID DEFAULT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX uniq_d3edb1e02693382e ON production (application_uuid)');
        $this->addSql('COMMENT ON COLUMN production.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN production.application_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE production_module (production_id UUID NOT NULL, module_id INT NOT NULL, PRIMARY KEY(production_id, module_id))');
        $this->addSql('CREATE INDEX idx_c384b275ecc6147f ON production_module (production_id)');
        $this->addSql('CREATE INDEX idx_c384b275afc2b591 ON production_module (module_id)');
        $this->addSql('COMMENT ON COLUMN production_module.production_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE subscription (id INT NOT NULL, name VARCHAR(60) NOT NULL, percentage SMALLINT DEFAULT 10 NOT NULL, app_type SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT fk_c11d7dd1a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_promotion ADD CONSTRAINT fk_c1fdf035139df194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_promotion ADD CONSTRAINT fk_c1fdf035a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_history ADD CONSTRAINT fk_3ef37ea1a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE production_history ADD CONSTRAINT fk_32affd96ecc6147f FOREIGN KEY (production_id) REFERENCES production (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE production_history ADD CONSTRAINT fk_32affd96afc2b591 FOREIGN KEY (module_id) REFERENCES module (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE developer ADD CONSTRAINT fk_65fb8b9a2693382e FOREIGN KEY (application_uuid) REFERENCES application (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_module ADD CONSTRAINT fk_c5d23eaf7acd6ec2 FOREIGN KEY (subscription_in) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription_module ADD CONSTRAINT fk_c5d23eafafc2b591 FOREIGN KEY (module_id) REFERENCES module (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application_subscription ADD CONSTRAINT fk_d3af8a3e030acd FOREIGN KEY (application_id) REFERENCES application (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application_subscription ADD CONSTRAINT fk_d3af8a9a1887dc FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application_subscription ADD CONSTRAINT fk_d3af8a9e085865 FOREIGN KEY (create_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application_module_history ADD CONSTRAINT fk_eb0e10623e030acd FOREIGN KEY (application_id) REFERENCES application (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application_module_history ADD CONSTRAINT fk_eb0e1062afc2b591 FOREIGN KEY (module_id) REFERENCES module (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT fk_a45bddc17e3c61f9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE developer_module ADD CONSTRAINT fk_f9c46abf64dd9267 FOREIGN KEY (developer_id) REFERENCES developer (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE developer_module ADD CONSTRAINT fk_f9c46abfafc2b591 FOREIGN KEY (module_id) REFERENCES module (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE production ADD CONSTRAINT fk_d3edb1e02693382e FOREIGN KEY (application_uuid) REFERENCES application (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE production_module ADD CONSTRAINT fk_c384b275ecc6147f FOREIGN KEY (production_id) REFERENCES production (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE production_module ADD CONSTRAINT fk_c384b275afc2b591 FOREIGN KEY (module_id) REFERENCES module (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
