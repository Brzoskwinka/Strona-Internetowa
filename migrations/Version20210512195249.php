<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210512195249 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compatibility (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, slave_id INT NOT NULL, INDEX IDX_73CC28647E3C61F9 (owner_id), INDEX IDX_73CC28642B29BD08 (slave_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compatibility ADD CONSTRAINT FK_73CC28647E3C61F9 FOREIGN KEY (owner_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE compatibility ADD CONSTRAINT FK_73CC28642B29BD08 FOREIGN KEY (slave_id) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE compatibility');
    }
}
