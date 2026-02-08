<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105103026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // User Table
        $user = $schema->createTable('user');
        $user->addColumn('id', 'integer', ['autoincrement' => true]);
        $user->addColumn('email', 'string', ['length' => 180]);
        $user->addColumn('roles', 'json');
        $user->addColumn('password', 'string', ['length' => 255]);
        $user->setPrimaryKey(['id']);
        $user->addUniqueIndex(['email'], 'UNIQ_IDENTIFIER_EMAIL');

        // Project Table
        $project = $schema->createTable('project');
        $project->addColumn('id', 'integer', ['autoincrement' => true]);
        $project->addColumn('title', 'string', ['length' => 255]);
        $project->addColumn('description', 'text');
        $project->addColumn('image', 'string', ['length' => 255, 'notnull' => false]);
        $project->addColumn('link', 'string', ['length' => 255, 'notnull' => false]);
        $project->addColumn('technologies', 'string', ['length' => 255, 'notnull' => false]);
        $project->addColumn('duration', 'string', ['length' => 255, 'notnull' => false]);
        $project->setPrimaryKey(['id']);

        // Messenger Table
        $messenger = $schema->createTable('messenger_messages');
        $messenger->addColumn('id', 'bigint', ['autoincrement' => true]);
        $messenger->addColumn('body', 'text');
        $messenger->addColumn('headers', 'text');
        $messenger->addColumn('queue_name', 'string', ['length' => 190]);
        $messenger->addColumn('created_at', 'datetime');
        $messenger->addColumn('available_at', 'datetime');
        $messenger->addColumn('delivered_at', 'datetime', ['notnull' => false]);
        $messenger->setPrimaryKey(['id']);
        $messenger->addIndex(['queue_name', 'available_at', 'delivered_at'], 'IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('user');
        $schema->dropTable('project');
        $schema->dropTable('messenger_messages');
    }
}
