<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\customevents\variables;

use superbig\customevents\CustomEvents;

use Craft;

/**
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 */
class CustomEventsVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param       $eventHandle
     * @param array $options
     *
     * @return string
     * @internal param null $optional
     *
     */
    public function createEvent ($eventHandle, $options = [])
    {
        return CustomEvents::$plugin->customEventsService->createEvent($eventHandle, $options);
    }

    public function createEventForElement ($eventHandle, $element)
    {
        return CustomEvents::$plugin->customEventsService->createEvent($eventHandle, [ 'element' => $element ]);
    }

    public function getEventsForElement ($element = null)
    {
        return CustomEvents::$plugin->customEventsService->getEventsForElement($element);
    }


    public function getEventsByHandle ($handle = null)
    {
        return CustomEvents::$plugin->customEventsService->getEventsByHandle($handle);
    }
}
