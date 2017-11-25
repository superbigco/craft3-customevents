<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\customevents\assetbundles\CustomEvents;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 */
class CustomEventsAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@superbig/customevents/assetbundles/customevents/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/CustomEvents.js',
        ];

        $this->css = [
            'css/CustomEvents.css',
        ];

        parent::init();
    }
}
