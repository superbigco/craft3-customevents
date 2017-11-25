<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\customevents;

use superbig\customevents\services\CustomEventsService as CustomEventsServiceService;
use superbig\customevents\variables\CustomEventsVariable;
use superbig\customevents\models\Settings;
use superbig\customevents\fields\Log as LogField;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\console\Application as ConsoleApplication;
use craft\web\UrlManager;
use craft\services\Fields;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class CustomEvents
 *
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 *
 * @property  CustomEventsServiceService $customEventsService
 * @method Settings getSettings()
 */
class CustomEvents extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var CustomEvents
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init ()
    {
        parent::init();
        self::$plugin = $this;

        if ( Craft::$app instanceof ConsoleApplication ) {
            $this->controllerNamespace = 'superbig\customevents\console\controllers';
        }

        $urlSegment = $this->getSettings()->urlSegment;

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) use ($urlSegment) {
                $event->rules[ $urlSegment . '/event' ] = 'custom-events/default/event';
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['cpActionTrigger1'] = 'custom-events/default/do-something';
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('customEvents', CustomEventsVariable::class);
            }
        );

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = LogField::class;
            }
        );

        Craft::info(
            Craft::t(
                'custom-events',
                '{name} plugin loaded',
                [ 'name' => $this->name ]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel ()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml (): string
    {
        return Craft::$app->view->renderTemplate(
            'custom-events/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
