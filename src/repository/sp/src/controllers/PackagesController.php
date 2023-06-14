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
use verbb\supertable\SuperTable;

use lantra\sp\helpers\LantraHelper;
use lantra\sp\Plugin as Lantra;

class PackagesController extends BaseController
{
    /**
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionExternalStatus()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        $userId = Craft::$app->request->getRequiredParam('userId');
        $externalStatus =  Craft::$app->request->getParam('externalStatus');
        $ids =  Craft::$app->request->getParam('ids', []);
        $updated = 0;
        foreach ($ids as $id) {
            if (null == $package = Craft::$app->entries->getEntryById($id)) {
                continue;
            }
            $externalAssessor = $externalStatus == 'selected' ? [$userId] : [];
            $package->setFieldValue('externalStatus', $externalStatus);
            $package->setFieldValue('externalAssessor', $externalAssessor);

            if (Craft::$app->elements->saveElement($package)) {
                $updated++;
            }

            ## add/remove external reviews
            if ($externalStatus == 'selected') {
                Lantra::$app->packages->stepAddExternal($package, $userId);
            }
            else {
                Lantra::$app->packages->stepRemoveExternal($package);
            }
        }
        $url = '/management/taskbooks/external?filter=' . ($externalStatus == 'selected' ? 'selected' : 'notSelected');
        $this->_returnMessage($updated . ' packages ' . $externalStatus == 'selected' ? 'selected' : 'unselected', true, $url);
    }

    /**
 * @return void|\yii\web\Response
 * @throws \Twig\Error\LoaderError
 * @throws \Twig\Error\RuntimeError
 * @throws \Twig\Error\SyntaxError
 * @throws \yii\base\Exception
 * @throws \yii\web\BadRequestHttpException
 */
    public function actionLoadTemplate()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        ## get the posted id
        $packageId =  Craft::$app->request->getParam('packageId');
        if (null == $package = Craft::$app->entries->getEntryById($packageId)) {
            return $this->_returnError('Package not found.');
        }
        $template =  Craft::$app->request->getParam('template');
        $t = $template == 'evidence' ? 'packageEvidence' : 'packageComments';
        $params = [
            'package'       => $package,
            'dateFormat'    => LantraHelper::setting('themeDateFormat', 'd-m-Y'),
            'taskbookLabel' => LantraHelper::setting('taskbookLabel')
        ];
        return Craft::$app->view->renderTemplate('_includes/taskbooks/' . $t, $params);
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
                else {
                    $result = isset($data['result']) ? $data['result'] == '1' : true;
                    if ($step->reviewStepType == 'external' || $step->reviewStepType == 'assessment') {
                        $sampled = true;
                        $passed = $result;
                    }
                    else {
                        $sampled = isset($data['sampled']) && $data['sampled'] == '1';
                        $passed = $sampled ? $result : true;
                    }
                    Lantra::$app->packages->stepUpdate($step, $sampled, $passed, $data['comment']);
                }
            }
        }
        if (!Craft::$app->elements->saveElement($package)) {
            return Craft::$app->urlManager->setRouteParams(['package' => $package]);
        }
        $this->_returnMessage('Package has been updated', true, 'management/taskbooks');
    }

    /**
     * @param int $packageId
     */
    public function actionExportPackage(int $entryId)
    {
        $this->requireLogin();
        if (null == $package = Entry::findOne($entryId)) {
            return $this->_returnError('Package not found.');
        }
        Lantra::$app->packages->exportPackage($package);
    }

    /**
     * @param int $entryId
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function actionResetPackage(int $entryId)
    {
        $this->requireLogin();
        $ids = Lantra::$app->results->resetPackageResults($entryId);
        $this->_returnMessage(count($ids) . ' results updated to draft.', 'true', 'management/taskbooks/manage/' . $entryId);
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

    /**
     * Get package units for pop-up
     *
    */
    public function actionBrowsePackages()
    {
        $this->requireLogin();
        $user = LantraHelper::getUser(Craft::$app->request->getParam('userId'));
        $packageId = Craft::$app->request->getParam('packageId', false);

        $response = [
            'success' => false,
            'message' => $packageId,
            'html' => ''
        ];

        $template = '_includes/evidence/packages';

        if ($packageId) {
            $package = Craft::$app->entries->getEntryById($packageId);
            $response['success'] = true;
            $response['html'] = Craft::$app->view->renderTemplate($template, ['package' => $package]);
            return $this->asJson($response);
        }        
    }

    public function actionSaveUnits()
    {
        $this->requireLogin();
        $this->requirePostRequest();
        $comments = Craft::$app->request->getParam('comments');
        $packageUnits = Craft::$app->request->getParam('packageUnits');
        $evidenceAssetIds = Craft::$app->request->getParam('evidenceAssetIds');
        $resultStatus = Craft::$app->request->getParam('resultStatus');
        $evidenceAssetIdsArr = [];
        if( $evidenceAssetIds ) $evidenceAssetIdsArr = explode('|', $evidenceAssetIds);
        $modules = [];
        $moduleUnits = [];

        $currentUser = Craft::$app->getUser()->getIdentity();
        $name = $currentUser->fullName;

        $stField = Craft::$app->getFields()->getFieldByHandle('resultComments');
        $stBlockTypes = SuperTable::$plugin->getService()->getBlockTypesByFieldId($stField->id);
        $stBlockType = $stBlockTypes[0];

        $resultComments = [];
        $resultComments['new1'] = [
            'type' => $stBlockType->id,
            'enabled' => true,
            'fields' => [
                'user' => [$currentUser->id],
                'comment' => $comments,
                'date' => date('Y-m-d')
            ]
        ];

        foreach ($packageUnits as $packageUnit) 
        {
            $packageArr = explode('|', $packageUnit);
            $unitId = $packageArr[0];
            $moduleId = $packageArr[1];
            if(!in_array($moduleId, $modules))
            {
                array_push($modules, $moduleId);
                // Save Module 
            }

            $moduleUnits[$moduleId] = $unitId;

            $resultEntry = Entry::find()->section('results')->relatedTo([$unitId])->one();

            if( !is_null($resultEntry) )
            {
                $resultEvidenceIdsArr = $resultEntry->resultEvidence->ids();
                if(!empty($evidenceAssetIdsArr)){
                    $resultEvidenceIds = array_merge($resultEvidenceIdsArr, $evidenceAssetIdsArr);
                    $fields['resultEvidence'] = array_unique($resultEvidenceIds);
                }
                $fields['resultStatus'] = $resultStatus;
                $resultEntry->setFieldValues($fields);
                Craft::$app->elements->saveElement($resultEntry);
            }
            else
            {
                // Save Unit
                $unit = new Entry();
                $unit->authorId = $currentUser->id;
                $unit->enabled = true;
                $unit->title = "unit {$unitId} " . $name;
                $unit->sectionId = LantraHelper::sectionId('results');
                $unit->typeId = 1; // Unit Result
                $fields['resultUnit'] = [$unitId];
                $fields['resultStatus'] = $resultStatus;
                // $fields['resultComments'] = $resultComments;
                // $fields['resultNotes'] = $comments;
                if(!empty($evidenceAssetIdsArr)){
                    $fields['resultEvidence'] = $evidenceAssetIdsArr;
                }
                $unit->setFieldValues($fields);
                Craft::$app->elements->saveElement($unit);
            }
        }
        return $this->redirectToPostedUrl();
    }
}