<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;

use craft\elements\Entry;
use craft\helpers\DateTimeHelper;
use verbb\supertable\elements\SuperTableBlockElement;

use lantra\sp\helpers\LantraHelper;
use lantra\sp\Plugin as Lantra;

class PackagesController extends BaseController
{
    /**
     * @return void|\yii\web\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionEvidence()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        ## get the posted id
        $packageId =  Craft::$app->request->getParam('packageId');
        if (null == $package = Craft::$app->entries->getEntryById($packageId)) {
            return $this->_returnError('Package not found.');
        }
        $template = '_includes/taskbooks/packageEvidence';
        $taskbookLabel = LantraHelper::setting('taskbookLabel');
        $response = Craft::$app->view->renderTemplate($template, ['package' => $package, 'taskbookLabel' => $taskbookLabel]);
        return $response;
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionAddModuleGroups()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        ## get the posted id
        $packageId =  Craft::$app->request->getParam('packageId');
        $singleType =  Craft::$app->request->getParam('singleType');
        if (null == $package = Entry::findOne($packageId)) {
            return $this->_returnError('Package not found.');
        }
        Lantra::$app->packages->applyOptionalModuleGroups($package, $singleType);
        if ($package->hasErrors()) {
            return $this->_returnError($package->getFirstErrors()[0]);
        }
        ## redirect to paypal if payment
        $this->_returnMessage('Package updated.');
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionRemoveModuleGroup()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        ## get the posted id
        $moduleGroupId =  Craft::$app->request->getParam('moduleGroupId');
        $packageId =  Craft::$app->request->getParam('packageId');
        if (null == $package = Entry::findOne($packageId)) {
            return $this->_returnError('Package not found.');
        }
        Lantra::$app->packages->removeModuleGroup($package, $moduleGroupId);
        $this->_returnMessage('Package updated.');
    }

    /**
     * User requests assessment for taskbook package
     *
     * @throws mixed
     */
    public function actionRequestAssessment()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        ## get the posted id
        $id =  Craft::$app->request->getParam('id');
        Lantra::$app->packages->stepRequest($id);
        return $this->_returnMessage('Assessment requested.', true);
    }

    /**
     * Deletes all results user results
     *
     * @throws mixed
     */
    public function actionDeleteResults()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        $taskbookLabel = LantraHelper::setting('taskbookLabel', 'Taskbook');
        ## get the posted id
        if (null == $id = Craft::$app->request->getParam('entryId')) {
            return $this->_returnError('Invalid ' . $taskbookLabel . ' ID!');
        }
        Lantra::$app->results->deletePackageResults($id);
        Craft::$app->elements->deleteElementById($id);
        return $this->_returnMessage($taskbookLabel . ' and all results deleted.', true);
    }

    /**
     * Saves user taskbook package
     *
     * @throws mixed
     */
    public function actionSavePackage()
    {
        $this->requireLogin();
        $userId = Craft::$app->request->getRequiredParam('userId');
        $fields = Craft::$app->request->getRequiredParam('fields');
        $user = Craft::$app->users->getUserById($userId);

        if (!$user) {
            return $this->_returnError('Invalid user [userId = ' . $userId .'].');
        }

        $package = new Entry();
        $package->authorId = $userId;
        $package->enabled = true;
        $package->sectionId = LantraHelper::sectionId('packages');
        $package->typeId = LantraHelper::entryTypeId('packages');
        $package->setFieldValues($fields);

        if (!Craft::$app->elements->saveElement($package)) {
            return Craft::$app->urlManager->setRouteParams(['package' => $package]);
        }

        $this->_returnMessage('Please continue to PayPal to make payment.', 'true', 'profile/taskbooks');
    }

    /**
     * Update taskbook package
     *
     * @throws mixed
     */
    public function actionUpdatePackage()
    {
        $this->requireLogin();
        $packageId = Craft::$app->request->getRequiredParam('packageId');
        $steps = Craft::$app->request->getRequiredParam('steps');
        $assessments = Craft::$app->request->getParam('assessments');
        if (null == $package = Craft::$app->entries->getEntryById($packageId)) {
            return $this->_returnError('Invalid params [packageId = ' . $packageId . '].');
        }
        if ($assessments) {
            Lantra::$app->packages->assessment($package, $assessments);
        }
        foreach($package->packageReviews as $step) {
            if (isset($steps[$step->id])) {
                $data = $steps[$step->id];
                if (isset($data['manager'])) {
                    Lantra::$app->packages->stepAssign($step, $data['manager']);
                }
                if (isset($data['result']) && $data['result'] !== '') {
                    ## result is always passed if not sampled
                    $sampled = isset($data['sampled']) && $data['sampled'] == '1';
                    $passed = !isset($data['sampled']) || $sampled ? $data['result'] == '1' : true;
                    Lantra::$app->packages->stepUpdate($step, $sampled, $passed, $data['comment']);
                }
            }
        }
        if (!Craft::$app->elements->saveElement($package)) {
            return Craft::$app->urlManager->setRouteParams(['package' => $package]);
        }
        $redirect = LantraHelper::packageUrl($package->authorId, $packageId);
        $this->_returnMessage('Package has been updated', true, $redirect);
    }

    /**
     * Saves user taskbook package
     *
     * @throws mixed
     */
    public function actionPackageAssessor()
    {
        $this->requireLogin();
        $userId = Craft::$app->request->getParam('userId');
        $packageId = Craft::$app->request->getParam('packageId');
        if (null == $package = SuperTableBlockElement::findOne($packageId)) {
            return $this->_returnError('Invalid params [packageId = ' . $packageId . '].');
        }
        $assessor = $userId ? Craft::$app->users->getUserById($userId) :null;
        $package->setFieldValue('packageAssessor', [$userId]);
        if (!Craft::$app->elements->saveElement($package)) {
            return $this->_returnError($package->getFirstErrors()[0]);
        }
        if ($assessor) {
            $message = $assessor->fullname . ' assigned as assessor';
        }
        else {
            $message = 'Assessor unassigned.';
        }
        $this->_returnMessage($message, 'true');
    }
}