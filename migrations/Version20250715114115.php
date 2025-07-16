<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250715114115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE upsell (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, price INT NOT NULL, duration INT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upsell_service (upsell_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_F6AF839DC1E6992E (upsell_id), INDEX IDX_F6AF839DED5CA9E6 (service_id), PRIMARY KEY(upsell_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE upsell_service ADD CONSTRAINT FK_F6AF839DC1E6992E FOREIGN KEY (upsell_id) REFERENCES upsell (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE upsell_service ADD CONSTRAINT FK_F6AF839DED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE upsell_service DROP FOREIGN KEY FK_F6AF839DC1E6992E');
        $this->addSql('ALTER TABLE upsell_service DROP FOREIGN KEY FK_F6AF839DED5CA9E6');
        $this->addSql('DROP TABLE upsell');
        $this->addSql('DROP TABLE upsell_service');
    }
}
