<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\customevents\records;

use superbig\customevents\CustomEvents;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 *
 * @property string       $eventName
 * @property string       $eventHandle
 * @property integer|null $userId
 * @property integer|null $siteId
 * @property integer|null $elementId
 * @property string|null  $elementType
 * @property string       $title
 * @property array        $snapshot
 * @property array        $location
 * @property string       $ip
 * @property string       $userAgent
 * @property \DateTime|null       $dateCreated
 */
class CustomEventsRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function tableName ()
    {
        return '{{%customevents_log}}';
    }
}
