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

use lantra\sp\Plugin as Lantra;

class PackagesController extends BaseController
{
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
     * @param $packageId
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionPay($packageId)
    {
        $spBlock = Craft::$app->elements->getElementById($packageId);
        $spBlock->setFieldValue('packagePaid', 1);
        Craft::$app->elements->saveElement($spBlock);
        $this->_returnMessage('Package Paid', true, 'profile/taskbooks');
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
        $package->sectionId = 15;
        $package->typeId = 20;
        $package->setFieldValues($fields);

        if (!Craft::$app->elements->saveElement($package)) {
            return Craft::$app->urlManager->setRouteParams(['package' => $package]);
        }

        $this->_returnMessage('Please continue to PayPal to make payment.', 'true', 'profile/taskbooks/view/' . $package->id);
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
        if (null == $package = Craft::$app->entries->getEntryById($packageId)) {
            return $this->_returnError('Invalid params [packageId = ' . $packageId . '].');
        }

        foreach($package->packageReviews as $step) {
            if (isset($steps[$step->id])) {
                $data = $steps[$step->id];
                if (isset($data['manager'])) {
                    Lantra::$app->packages->stepAssign($step, $data['manager']);
                }
                if (isset($data['result']) && $data['result'] !== '') {
                    Lantra::$app->packages->stepUpdate($step, $data['result'] == '1', $data['comment']);
                }
            }
        }

        if (!Craft::$app->elements->saveElement($package)) {
            return Craft::$app->urlManager->setRouteParams(['package' => $package]);
        }

        $redirect = '/cpd/' . $package->authorId;
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