<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https:##coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers\cp;

use Craft;
use craft\elements\Entry;
use craft\elements\User;
use craft\web\Controller;

use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;

class SpbaseController extends Controller
{
    /**
     * Skills+ Base
     *
     * @throws mixed
     */
    public function actionIndex()
    {
        $variables['site'] = Lantra::$app->spbase->getSite(false);
        $this->renderTemplate('sp/cp/spbase', $variables);
    }
}