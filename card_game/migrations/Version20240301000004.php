<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240301000004 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE game ADD player1_card3_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD player2_card3_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_FF232B31_P1C3 FOREIGN KEY (player1_card3_id) REFERENCES cards (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_FF232B31_P2C3 FOREIGN KEY (player2_card3_id) REFERENCES cards (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_FF232B31_P1C3');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_FF232B31_P2C3');
        $this->addSql('ALTER TABLE game DROP player1_card3_id');
        $this->addSql('ALTER TABLE game DROP player2_card3_id');
    }
}