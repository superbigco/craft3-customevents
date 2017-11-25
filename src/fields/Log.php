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
use superbig\customevents\models\CustomEventsModel;
use superbig\customevents\records\CustomEventsRecord;
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

    /**
     * @var array
     */
    public $tableColumns = [];

    /**
     * @var array
     */
    protected $availableTableColumns = [];

    public function init ()
    {
        parent::init();

        $this->availableTableColumns = CustomEventsModel::getTableColumns();
    }

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
            [ 'tableColumns', 'default', 'value' => [] ],
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
                'field'                 => $this,
                'availableTableColumns' => $this->availableTableColumns,
            ]
        );
    }

    public function getEnabledTableColumns ()
    {
        $tableColumns = $this->tableColumns;

        return array_filter($this->availableTableColumns, function ($key) use ($tableColumns) {
            return in_array($key, $tableColumns);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml ($value, ElementInterface $element = null): string
    {
        $view = Craft::$app->getView();

        // Register our asset bundle
        $view->registerAssetBundle(LogFieldAsset::class);

        // Get our id and namespace
        $id           = $view->formatInputId($this->handle);
        $namespacedId = $view->namespaceInputId($id);


        // Render the input template
        return $view->renderTemplate(
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
