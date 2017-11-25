<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\customevents\services;

use craft\base\ElementInterface;
use superbig\customevents\CustomEvents;

use Craft;
use craft\base\Component;
use superbig\customevents\models\CustomEventsModel;
use superbig\customevents\records\CustomEventsRecord;

/**
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 */
class CustomEventsService extends Component
{
    // Public Methods
    // =========================================================================

    public function getEventsForElement (ElementInterface $element)
    {
        $elementId   = $element->getId();
        $elementType = get_class($element);

        return $this->getEventsByAttributes([ 'elementId' => $elementId, 'elementType' => $elementType ]);
    }

    public function getEventsByHandle ($handle = null)
    {
        return $this->getEventsByAttributes([ 'eventHandle' => $handle ]);
    }

    public function getEventsByAttributes ($attributes = [])
    {
        $models  = null;
        $records = CustomEventsRecord::findAll($attributes);

        if ( $records ) {
            foreach ($records as $record) {
                $models[] = CustomEventsModel::createFromRecord($record);
            }
        }

        return $models;
    }

    /**
     * @param null|string $handle
     * @param array       $options
     */
    public function createEvent ($handle = null, $options = [])
    {
        $request  = Craft::$app->request;
        $settings = CustomEvents::$plugin->getSettings();
        $events   = $settings->events;
        $unique   = false;

        var_dump($events);

        if ( $handle && !empty($events[ $handle ]) ) {
            $event  = $events[ $handle ];
            $unique = !empty($event['unique']) || !empty($options['unique']);

            $model              = new CustomEventsModel();
            $model->eventHandle = $handle;
            $model->eventName   = $event['name'];
            $model->ip          = $request->getUserIP();
            $model->userAgent   = $request->getUserAgent();
            $model->siteId      = Craft::$app->getSites()->currentSite->id;

            if ( $user = Craft::$app->getUser() ) {
                $model->userId = $user->getId();
            }

            if ( !empty($options['element']) && $options['element'] instanceof ElementInterface ) {
                /** @var ElementInterface $element */
                $element            = $options['element'];
                $model->elementId   = $element->getId();
                $model->elementType = get_class($element);

                if ( $element->hasTitles() ) {
                    $model->title = $element->title;
                }
            }

            $this->saveRecord($model, $unique);
        }
    }

    /*
     * @return mixed
     */
    public function saveRecord (CustomEventsModel $model, $unique = true)
    {
        if ( $unique ) {
            $existingRecord = CustomEventsRecord::findOne([
                'eventName'   => $model->eventName,
                'eventHandle' => $model->eventHandle,
                'title'       => $model->title,
                'elementId'   => $model->elementId,
                'elementType' => $model->elementType,
                'ip'          => $model->ip,
                'userAgent'   => $model->userAgent,
                'siteId'      => $model->siteId,
            ]);

            var_dump($existingRecord);

            if ( $existingRecord ) {
                return true;
            }
        }

        if ( $model->id ) {
            $record = CustomEventsRecord::findOne($model->id);
        }
        else {
            $record = new CustomEventsRecord();
        }

        $record->eventName   = $model->eventName;
        $record->eventHandle = $model->eventHandle;
        $record->title       = $model->title;
        $record->elementId   = $model->elementId;
        $record->elementType = $model->elementType;
        $record->ip          = $model->ip;
        $record->userAgent   = $model->userAgent;
        $record->siteId      = $model->siteId;

        return $record->save();
    }
}
