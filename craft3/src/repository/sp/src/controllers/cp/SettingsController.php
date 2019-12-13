<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers\cp;

use Craft;
use craft\elements\Entry;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\User;
use craft\web\Controller;

use lantra\sp\Plugin as Lantra;
use lantra\sp\models\Settings as SettingsModel;


class SettingsController extends Controller
{
    /**
     * @throws HttpException
     */
    public function actionIndex()
    {
        $this->requireAdmin(false);

        $settingsModel = new SettingsModel;
        $settingsModel->setAttributes(Lantra::getInstance()->getSettings());
        $variables['settings'] = $settingsModel;
        $config['version'] = Lantra::getInstance()->getVersion();

        ## config for logo asset
        $volume = Craft::$app->volumes->getVolumeByHandle('theme');
        $themeFolder = Craft::$app->assets->getRootFolderByVolumeId($volume->id);
        $config['themeFolder'] = ['folder:'.$themeFolder->id.':single'];

        ## config for navigation entries
        $config['pagesSection'] = ['section:14'];
        $config['companiesSection'] = ['section:3'];
        $config['jobRoleCategoryGroup'] = ['group:1'];
        $config['assetsElementType'] = Asset::class;
        $config['entryElementType'] = Entry::class;
        $config['categoryElementType'] = Category::class;
        $variables['config'] = $config;

        $this->renderTemplate('sp/cp/settings/index', $variables);
    }

    /**
     * @throws HttpException
     */
    public function actionQueue()
    {
        $this->requireAdmin(false);
        $this->renderTemplate('sp/cp/queue');
    }

    /**
     * @throws HttpException
     */
    public function actionCache()
    {
        $this->requireAdmin(false);
        $this->renderTemplate('sp/cp/cache');
    }

    /**
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveSettings()
    {
        $this->requirePostRequest();
        $settings = Craft::$app->request->getParam('settings');

        if (Lantra::$app->settings->saveSettings($settings)) {
            Craft::$app->session->setNotice('Settings saved.');
            $this->redirectToPostedUrl();
        } else {
            Craft::$app->session->setError('Settings not saved.');
            Craft::$app->urlManager->setRouteParams(array('settings' => $settings));
        }
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteJob()
    {
        $elementId = Craft::$app->request->getParam('elementId');
        if ($elementId == 'all') {
            Lantra::$app->queue->clear();
        }
        else {
            Lantra::$app->queue->delete($elementId);
        }
        Craft::$app->session->setNotice('Queue updated.');
        $this->redirect('lantra/settings/queue');
    }
}