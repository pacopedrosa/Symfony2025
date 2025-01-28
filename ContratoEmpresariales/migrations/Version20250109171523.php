<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109171523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE persona_contacto ADD relacion_comunicacion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE persona_contacto ADD CONSTRAINT FK_C7925D7DAC02B96C FOREIGN KEY (relacion_comunicacion_id) REFERENCES comunicacion (id)');
        $this->addSql('CREATE INDEX IDX_C7925D7DAC02B96C ON persona_contacto (relacion_comunicacion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE persona_contacto DROP FOREIGN KEY FK_C7925D7DAC02B96C');
        $this->addSql('DROP INDEX IDX_C7925D7DAC02B96C ON persona_contacto');
        $this->addSql('ALTER TABLE persona_contacto DROP relacion_comunicacion_id');
    }
}
