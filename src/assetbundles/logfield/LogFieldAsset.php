<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\customevents\assetbundles\logfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 */
class LogFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@superbig/customevents/assetbundles/logfield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Log.js',
        ];

        $this->css = [
            'css/Log.css',
        ];

        parent::init();
    }
}
