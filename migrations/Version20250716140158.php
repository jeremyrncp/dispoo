<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250716140158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE timeslot (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, days LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', start_time VARCHAR(255) NOT NULL, end_time VARCHAR(255) NOT NULL, number_appointments INT DEFAULT NULL, delay_between_appointments INT NOT NULL, INDEX IDX_3BE452F77E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE timeslot ADD CONSTRAINT FK_3BE452F77E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timeslot DROP FOREIGN KEY FK_3BE452F77E3C61F9');
        $this->addSql('DROP TABLE timeslot');
    }
}
