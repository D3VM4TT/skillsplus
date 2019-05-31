<?php

namespace Craft;

class Lantra_SettingsController extends BaseController
{
    /**
     * @throws HttpException
     */
    public function actionIndex()
    {
        $settingsModel = new Lantra_SettingsModel;

        $settings = craft()->db->createCommand()
            ->select('settings')
            ->from('plugins')
            ->where('class=:class', array(':class' => 'Lantra'))
            ->queryScalar();

        $settings = JsonHelper::decode($settings);
        $settingsModel->setAttributes($settings);
        $variables['settings'] = $settingsModel;

        ## config for logo asset
        $themeFolder = craft()->assets->getRootFolderBySourceId(4)->id;
        $variables['themeFolder'] = ['folder:'.$themeFolder.':single'];

        ## config for navigation entries
        $variables['pagesSection'] = ['section:14'];
        $variables['companiesSection'] = ['section:3'];
        $variables['jobRoleCategoryGroup'] = ['group:1'];

        $this->renderTemplate('lantra/settings', $variables);
    }

    /**
     * @throws HttpException
     */
    public function actionSaveSettings()
    {
        $this->requirePostRequest();
        $settings = craft()->request->getPost('settings');

        if (craft()->lantra_settings->saveSettings($settings)) {
            craft()->userSession->setNotice(Craft::t('Settings saved.'));
            $this->redirectToPostedUrl();
        } else {
            craft()->userSession->setError(Craft::t('Settings not saved.'));
            craft()->urlManager->setRouteVariables(array('settings' => $settings));
        }
    }
}