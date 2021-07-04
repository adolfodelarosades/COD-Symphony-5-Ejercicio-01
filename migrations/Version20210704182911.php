<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210704182911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autor (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(10) NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE editorial (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fondo (id INT AUTO_INCREMENT NOT NULL, editorial_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, isbn VARCHAR(20) NOT NULL, edicion INT DEFAULT NULL, publicacion INT DEFAULT NULL, categoria VARCHAR(255) NOT NULL, INDEX IDX_2DC0A6E5BAF1A24D (editorial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fondo_autor (fondo_id INT NOT NULL, autor_id INT NOT NULL, INDEX IDX_44096994AA510E89 (fondo_id), INDEX IDX_4409699414D45BBE (autor_id), PRIMARY KEY(fondo_id, autor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fondo ADD CONSTRAINT FK_2DC0A6E5BAF1A24D FOREIGN KEY (editorial_id) REFERENCES editorial (id)');
        $this->addSql('ALTER TABLE fondo_autor ADD CONSTRAINT FK_44096994AA510E89 FOREIGN KEY (fondo_id) REFERENCES fondo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fondo_autor ADD CONSTRAINT FK_4409699414D45BBE FOREIGN KEY (autor_id) REFERENCES autor (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fondo_autor DROP FOREIGN KEY FK_4409699414D45BBE');
        $this->addSql('ALTER TABLE fondo DROP FOREIGN KEY FK_2DC0A6E5BAF1A24D');
        $this->addSql('ALTER TABLE fondo_autor DROP FOREIGN KEY FK_44096994AA510E89');
        $this->addSql('DROP TABLE autor');
        $this->addSql('DROP TABLE editorial');
        $this->addSql('DROP TABLE fondo');
        $this->addSql('DROP TABLE fondo_autor');
    }
}
