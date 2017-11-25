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

/**
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * URL segment for controllers
     *
     * @var string
     */
    public $urlSegment = '_ce';

    /**
     * Disable for logged in users
     *
     * @var boolean
     */
    public $disableForLoggedInUsers = false;

    /**
     * Disable for guests
     *
     * @var boolean
     */
    public $disableForGuests = false;

    // Public Methods
    // =========================================================================

    /**
     * @var array
     */
    public $events = [];

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ 'events', 'default', 'value' => [] ],
        ];
    }
}
