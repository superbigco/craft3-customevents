<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\customevents\models;

use superbig\customevents\CustomEvents;

use Craft;
use craft\base\Model;
use superbig\customevents\records\CustomEventsRecord;

/**
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 */
class CustomEventsModel extends Model
{
    const FILLABLE_PROPERTIES = [
        'id',
        'siteId',
        'eventName',
        'eventHandle',
        'userId',
        'elementId',
        'elementType',
        'title',
        'snapshot',
        'ip',
        'userAgent',
        'location',
        'dateCreated',
    ];

    // Public Properties
    // =========================================================================

    /**
     * @var integer|null
     */
    public $id = null;

    /**
     * @var integer|null
     */
    public $siteId = null;

    /**
     * @var string
     */
    public $eventName = '';

    /**
     * @var string
     */
    public $eventHandle = '';

    /**
     * @var integer|null
     */
    public $userId = null;

    /**
     * @var integer|null
     */
    public $elementId = null;

    /**
     * @var string|null
     */
    public $elementType = null;

    /**
     * @var string
     */
    public $title = '';

    /**
     * @var array
     */
    public $snapshot = [];

    /**
     * @var string
     */
    public $ip = '';

    /**
     * @var string
     */
    public $userAgent = '';

    /**
     * @var array
     */
    public $location = [];

    /**
     * @var \DateTime|null
     */
    public $dateCreated = null;

    protected $_user = null;

    protected $_element = null;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ [ 'eventName' ], 'required' ],
        ];
    }

    public function getElement ()
    {
        if ( !$this->_element ) {
            $this->_element = Craft::$app->getElements()->getElementById($this->elementId, $this->elementType, $this->siteId);
        }

        return $this->_element;
    }

    /**
     * Get the associated user, if any
     *
     * @return \craft\elements\User|null
     */
    public function getUser ()
    {
        if ( !$this->_user ) {
            if ( $this->userId === null ) {
                return null;
            }

            $this->_user = Craft::$app->getUsers()->getUserById($this->userId);
        }

        return $this->_user;
    }

    /**
     * @return null|string
     */
    public function getName ()
    {
        if ( $this->getUser() && $this->getUser()->getName() ) {
            return $this->getUser()->getName();
        }

        return '';
    }

    public static function createFromRecord (CustomEventsRecord $record)
    {
        $model = new CustomEventsModel();

        foreach (CustomEventsModel::FILLABLE_PROPERTIES as $key) {
            $model->$key = $record->$key;
        }

        return $model;
    }

    public static function getTableColumns ()
    {
        return [
            'eventName'   => Craft::t('custom-events', 'Event name'),
            'eventHandle' => Craft::t('custom-events', 'Event handle'),
            'user'        => Craft::t('custom-events', 'User'),
            'title'       => Craft::t('custom-events', 'Element title'),
            'snapshot'    => Craft::t('custom-events', 'Snapshot'),
            'ip'          => Craft::t('custom-events', 'IP address'),
            'userAgent'   => Craft::t('custom-events', 'User agent'),
            'location'    => Craft::t('custom-events', 'Location'),
            'dateCreated' => Craft::t('custom-events', 'Date'),
        ];
    }
}