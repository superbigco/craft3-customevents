<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\customevents\migrations;

use superbig\customevents\CustomEvents;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    public $tableName = '{{%customevents_log}}';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

   /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

        $tableSchema = Craft::$app->db->schema->getTableSchema($this->tableName);
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                $this->tableName,
                [
                    'id' => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),
                    'siteId' => $this->integer()->notNull(),

                    'userId'      => $this->integer()->null()->defaultValue(null),
                    'elementId'   => $this->integer()->null()->defaultValue(null),
                    'elementType' => $this->string(255)->null()->defaultValue(null),
                    'eventName'   => $this->string(255)->notNull()->defaultValue(''),
                    'eventHandle' => $this->string(255)->notNull()->defaultValue(''),
                    'title'       => $this->string(255)->null()->defaultValue(null),
                    'description' => $this->string(255)->null()->defaultValue(null),
                    'ip'          => $this->string(255)->null()->defaultValue(null),
                    'userAgent'   => $this->string(255)->null()->defaultValue(null),
                    'snapshot'    => $this->text()->null()->defaultValue(null),
                    'location'    => $this->text()->null()->defaultValue(null),
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * @return void
     */
    protected function createIndexes()
    {
        $this->createIndex(
            $this->db->getIndexName(
                $this->tableName,
                'elementId',
                false
            ),
            $this->tableName,
            'elementId',
            false
        );

        $this->createIndex(
            $this->db->getIndexName(
                $this->tableName,
                'userId',
                false
            ),
            $this->tableName,
            'userId',
            false
        );

        $this->createIndex(
            $this->db->getIndexName(
                $this->tableName,
                'elementType',
                false
            ),
            $this->tableName,
            'elementType',
            false
        );

        // Additional commands depending on the db driver
        switch ($this->driver) {
            case DbConfig::DRIVER_MYSQL:
                break;
            case DbConfig::DRIVER_PGSQL:
                break;
        }
    }

    /**
     * @return void
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey(
            $this->db->getForeignKeyName($this->tableName, 'siteId'),
            $this->tableName,
            'siteId',
            '{{%sites}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName($this->tableName, 'elementId'),
            $this->tableName,
            'elementId',
            '{{%elements}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            $this->db->getForeignKeyName($this->tableName, 'userId'),
            $this->tableName,
            'userId',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists($this->tableName);
    }
}
