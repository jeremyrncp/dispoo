<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250716153851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, active TINYINT(1) DEFAULT NULL, price_cents INT DEFAULT NULL, duration INT NOT NULL, start_date_time DATETIME NOT NULL, end_date_time DATETIME NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, postla_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_FE38F8447E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment_item (id INT AUTO_INCREMENT NOT NULL, appointment_id INT NOT NULL, price_cents INT NOT NULL, price INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_CD8EDDFE5B533F9 (appointment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F8447E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE appointment_item ADD CONSTRAINT FK_CD8EDDFE5B533F9 FOREIGN KEY (appointment_id) REFERENCES appointment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F8447E3C61F9');
        $this->addSql('ALTER TABLE appointment_item DROP FOREIGN KEY FK_CD8EDDFE5B533F9');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE appointment_item');
    }
}
