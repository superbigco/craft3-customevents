<?php
/**
 * Custom Events plugin for Craft CMS 3.x
 *
 * Trigger custom events from templates and plugins that other plugins can hook into.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2017 Superbig
 */

namespace superbig\customevents\controllers;

use superbig\customevents\CustomEvents;

use Craft;
use craft\web\Controller;

/**
 * @author    Superbig
 * @package   CustomEvents
 * @since     2.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = [ 'index', 'event' ];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex ()
    {
        $result = 'Welcome to the DefaultController actionIndex() method';

        return $result;
    }

    /**
     * @return mixed
     */
    public function actionEvent ()
    {
        $app       = Craft::$app;
        $request   = $app->getRequest();
        $handle    = $request->getParam('eventHandle');
        $elementId = $request->getParam('elementId');
        $svg       = '<svg width="1" height="1" viewBox="0 0 1 1" xmlns="http://www.w3.org/2000/svg"><title></title></svg>';

        /*Craft::$app->getResponse()
                   ->getHeaders()
                   ->set('Pragma', 'public')
                   ->set('Expires', '0')
                   ->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                   ->set('Cache-Control', 'private')
                   ->set('Content-Transfer-Encoding', 'binary');*/

        $options = [];

        if ( $elementId ) {
            $element = $app->getElements()->getElementById($elementId);

            if ( $element ) {
                $options = [ 'element' => $element ];
            }
        }

        CustomEvents::$plugin->customEventsService->createEvent($handle, $options);

        return $app->getResponse()->sendContentAsFile($svg, 'blank.svg', [
            'mimeType' => 'image/svg+xml',
        ]);
    }
}
