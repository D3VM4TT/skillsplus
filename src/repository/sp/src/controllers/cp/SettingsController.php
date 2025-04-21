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
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionIndex()
    {
        # $this->requireAdmin(false);
        $variables = [
            'config'    => $this->_config(),
            'settings'  => $this->_settings()
            ];
        $this->renderTemplate('sp/cp/settings/index', $variables);
    }

    /**
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionNotifications()
    {
        # $this->requireAdmin(false);
        $variables = [
            'config'    => $this->_config(),
            'settings'  => $this->_settings()
        ];
        $this->renderTemplate('sp/cp/notifications/index', $variables);

    }

    /**
     * @return array
     */
    private function _config()
    {
        $config = [];
        $config['version'] = Lantra::getInstance()->getVersion();

        ## config for logo asset
        $volume = Craft::$app->volumes->getVolumeByHandle('theme');
        $themeFolder = Craft::$app->assets->getRootFolderByVolumeId($volume->id);
        $config['themeFolder'] = ['folder:'.$themeFolder->uid];

        ## config for navigation entries
        $pagesSection = Craft::$app->sections->getSectionByHandle('pages');
        $config['pagesSection'] = ['section:'.$pagesSection->uid];
        $companiesSection = Craft::$app->sections->getSectionByHandle('companies');
        $config['companiesSection'] = ['section:'.$companiesSection->uid];
        $workflowsSection = Craft::$app->sections->getSectionByHandle('workflows');
        $config['workflowsSection'] = ['section:'.$workflowsSection->uid];
        $jobRolesCategoryGroup = Craft::$app->categories->getGroupByHandle('roles');
        $config['jobRoleCategoryGroup'] = ['group:'.$jobRolesCategoryGroup->uid];
        $config['assetsElementType'] = Asset::class;
        $config['entryElementType'] = Entry::class;
        $config['categoryElementType'] = Category::class;

        ## job roles for registration dropdown
        $jobRoles = Category::find()->group('roles')->all();
        $config['jobRoleOptions'] = [];
        foreach ($jobRoles as $jobRole) {
            $config['jobRoleOptions'][$jobRole->id] = $jobRole->title;
        }

        ## config for logo asset
        $volume = Craft::$app->volumes->getVolumeByHandle('theme');
        $themeFolder = Craft::$app->assets->getRootFolderByVolumeId($volume->id);
        $config['themeFolder'] = ['folder:'.$themeFolder->uid];

        return $config;
    }

    /**
     * @return SettingsModel
     */
    private function _settings()
    {
        $settingsModel = new SettingsModel;
        $settingsModel->setAttributes(Lantra::getInstance()->getSettings()->toArray());
        return $settingsModel;
    }

    /**
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionQueue()
    {
        $this->requireAdmin(false);
        $this->renderTemplate('sp/cp/queue');
    }

    /**
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionCache()
    {
        # $this->requireAdmin(false);
        $this->renderTemplate('sp/cp/cache');
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveSettings()
    {
        $this->requirePostRequest();
        $settings = Craft::$app->request->getParam('settings');
        if (!Lantra::$app->settings->saveSettings($settings)) {
            Craft::$app->session->setError('Settings not saved.');
            Craft::$app->urlManager->setRouteParams(['settings' => $settings]);
            return $this->redirectToPostedUrl();
        }
        Craft::$app->session->setNotice('Settings saved.');
        return $this->redirectToPostedUrl();
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
        $this->redirect('sp/queue');
    }
}