<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222144326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories CHANGE libelle_category libelle_category VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE task CHANGE name_task name_task VARCHAR(75) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE description_task description_task TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE priority_task priority_task VARCHAR(30) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
    }
}
