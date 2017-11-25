<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\customevents\fields;

use superbig\customevents\CustomEvents;
use superbig\customevents\assetbundles\logfield\LogFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use superbig\customevents\services\CustomEventsService;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 */
class Log extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $eventHandle = '';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName (): string
    {
        return Craft::t('custom-events', 'Custom Event Log');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            [ 'eventHandle', 'string' ],
            [ 'eventHandle', 'default', 'value' => '' ],
        ]);

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType (): string
    {
        return Schema::TYPE_STRING;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue ($value, ElementInterface $element = null)
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue ($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml ()
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'custom-events/_components/fields/Log_settings',
            [
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml ($value, ElementInterface $element = null): string
    {
        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(LogFieldAsset::class);

        // Get our id and namespace
        $id           = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);


        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'custom-events/_components/fields/Log_input',
            [
                'name'         => $this->handle,
                'value'        => $value,
                'field'        => $this,
                'id'           => $id,
                'namespacedId' => $namespacedId,
                'events'       => CustomEvents::$plugin->customEventsService->getEventsForElement($element),
            ]
        );
    }
}
