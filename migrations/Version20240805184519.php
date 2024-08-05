<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240805184519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO exchange_rate (from_currency, to_currency, rate, updated_at) VALUES
            ('rub', 'usd', 1, current_date)");

        $this->addSql("INSERT INTO exchange_rate (from_currency, to_currency, rate, updated_at) VALUES
            ('usd', 'rub', 8943, current_date)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM exchange_rate WHERE from_currency = 'rub' AND to_currency = 'usd'");
        $this->addSql("DELETE FROM exchange_rate WHERE from_currency = 'usd' AND to_currency = 'rub'");
    }
}
