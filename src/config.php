<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

/**
 * Custom Events config.php
 *
 * This file exists only as a template for the Custom Events settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'custom-events.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [

    'events' => [
        'viewedProposal' => [
            'name' => 'Viewed proposal'
        ],

        'acceptedProposal' => [
            'name'   => 'Accepted proposal',
            'unique' => true,
        ],
    ],

];
