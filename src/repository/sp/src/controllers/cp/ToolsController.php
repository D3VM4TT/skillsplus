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

class ToolsController extends Controller
{
    /**
     * Import Lantra Data
     *
     * @throws mixed
     */
    public function actionIndex()
    {
        if (false != $method = Craft::$app->request->getParam('method')) {
            if (method_exists($this, $method)) {
                return $this->$method();
            }
            Craft::$app->session->setError('Method ' . $method . ' does not exist!');
        }

        ## count results with notes
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->limit = null;
        $criteria->type = ['unitResult', 'userResult'];
        $criteria->resultNotes = ':notempty:';
        $criteria->status = null;
        $resultNotes = $criteria->count();

        $variables = [
            'resultCacheDateUpdated' => Lantra::$app->results->getResultCacheDateUpdated(),
            'resultLastDate' => Lantra::$app->results->getResultLastDate(),
            'dataCleanResultNotes' => $resultNotes,
            'dataCleanManagersChildrenTotal' => $this->getManagers(null, 'ManagersChildren', 1, true),
            'dataCleanManagersUserCompanyTotal' => $this->getManagers(null, 'ManagersUserCompany', 1, true),
            'dataCleanResultCacheTotal' => $this->getUsers(null, 'ResultCache', 1, true),
        ];
        $this->renderTemplate('sp/cp/tools', $variables);
    }

    /**
     * @param null $limit
     * @param null $dataCleanKey
     * @param bool $dataCleanValue
     * @param bool $count
     * @return mixed
     */
    private function getManagers($limit = null, $dataCleanKey = null, $dataCleanValue = false, $count = false) {
        $criteria = User::find();
        $criteria->groupId = [2,3];
        $criteria->admin = false;
        $criteria->limit = $limit;
        if ($dataCleanKey) {
            $fieldName = 'dataClean' . $dataCleanKey;
            $criteria->$fieldName = $dataCleanValue ? 1 : 0;
        }
        return $count ? $criteria->count() : $criteria->all();
    }

    /**
     * @param $userId
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    private function getUserUnitResults($userId) {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        $criteria->status = null;
        return $criteria;
    }

    /**
     * @param $name
     * @return array
     */
    private function getNames($name) {
        $parts = explode(' ', trim($name));
        if (count($parts) == 1) {
            $firstName = $parts[0];
            $lastName = '';
        } else if (count($parts) == 2) {
            $firstName = $parts[0];
            $lastName = $parts[1];
        } else {
            $lastName = array_pop($parts);
            $firstName = implode(' ', $parts);
        }

        return [utf8_encode($firstName), utf8_encode($lastName)];
    }

    /**
     * @param null $limit
     * @param null $dataCleanKey
     * @param bool $dataCleanValue
     * @param bool $count
     * @return mixed
     */
    private function getUsers($limit = null, $dataCleanKey = null, $dataCleanValue = false, $count = false) {
        $criteria = User::find();
        $criteria->groupId = [2,3,4];
        $criteria->admin = false;
        $criteria->limit = $limit;
        $criteria->order = 'id';
        if ($dataCleanKey) {
            $fieldName = 'dataClean' . $dataCleanKey;
            $criteria->$fieldName = $dataCleanValue ? 1 : 0;
        }
        return $count ? $criteria->count() : $criteria->all();
    }


    /**
     * @throws \CException
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function saveCompanies()
    {
        $topCompanies = Lantra::$app->structure->getCompanyChildren(null, false, null);
        if ($topCompanies) {
            foreach ($topCompanies as $company) {
                Craft::$app->elements->saveElement($company);
            }
        }
        Craft::$app->session->setNotice(Craft::t('sp', 'All companies saved.'));
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function saveUnits()
    {
        $criteria = Entry::find();
        $criteria->section = 'units';
        $criteria->limit = null;
        $units = $criteria->all();
        foreach ($units as $unit) {
            Craft::$app->elements->saveElement($unit);
        }
        Craft::$app->session->setNotice(Craft::t('sp', count($units) . ' units saved.'));
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function saveUsers()
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            Craft::$app->elements->saveElement($user);
        }
        Craft::$app->session->setNotice(Craft::t('sp', count($users) . ' users saved.'));
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function cleanUsernames()
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            $user->username = str_replace('..', '.', $user->username);
            $user->username = rtrim($user->username, '.');
            Craft::$app->elements->saveElement($user);
        }
        Craft::$app->session->setNotice(Craft::t('sp', count($users) . ' usernames cleaned.'));
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function fixLastNames()
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            if (empty($user->lastName)) {
                $names = $this->getNames($user->firstName);
                $user->firstName = $names[0];
                $user->lastName = $names[1];
                Craft::$app->elements->saveElement($user);
            }
        }
        Craft::$app->session->setNotice(Craft::t('sp', count($users). ' users with empty last names updated.'));
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function setManagerReadOnly()
    {
        $managers = $this->getManagers();
        $message = '';
        foreach ($managers as $user) {
            $user->setFieldValue('managerReadOnly', 1);
            if (!Craft::$app->elements->saveElement($user)) {
                $message .= ' ' . $user->fullName . ' not updated.';
            };
        }
        Craft::$app->session->setNotice(Craft::t('sp', count($managers) . ' managers updated.' . $message));
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function removeManagersChildren()
    {
        $managers = $this->getManagers(100, 'ManagersChildren', false);
        $message = '';
        foreach ($managers as $user) {
            $user->setFieldValue('dataCleanManagersChildren', 1);
            Craft::$app->elements->saveElement($user, false);
            $companies = Lantra::$app->users->getManagerCompanies($user, true);
            $companyIds = [];
            foreach ($companies as $company) {
                $companyIds[] = $company->id;
            }
            foreach ($companies as $company) {
                if ($company->companyParent->one() && in_array($company->companyParent->one()->id, $companyIds)) {
                    Lantra::$app->users->removeCompanyManager($company, $user);
                }
            }
        }
        Craft::$app->session->setNotice(Craft::t('sp', count($managers) . ' managers updated.' . $message));
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function dataResetManagersChildren()
    {
        Lantra::$app->settings->resetDataClean('ManagersChildren');
        Craft::$app->session->setNotice('All managers have been reset.');
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \yii\web\BadRequestHttpException
     */
    private function syncResultCache()
    {
        $updated = Lantra::$app->results->syncUserResultCache();
        Craft::$app->session->setNotice('Result cache has been updated for ' . $updated . ' users.');
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function setManagerUserCompany()
    {
        $managers = $this->getManagers(100, 'ManagersUserCompany', false);
        $message = '';
        foreach ($managers as $manager) {
            $manager->setFieldValue('dataCleanManagersUserCompany', 1);
            Craft::$app->elements->saveElement($manager, false);
            $companies = Lantra::$app->users->getManagerCompanies($manager, true);
            if (!$companies) {
                continue;
            }
            $companyId = null;
            foreach ($companies as $company) {
                $companyId = $company->id;
            }
            if ($companyId) {
                $manager->setFieldValue('userCompany', [$companyId]);
                if (!Craft::$app->elements->saveElement($manager, false)) {
                    $message .= ' ' . $manager->fullName . ' not updated.';
                };
            }
        }
        Craft::$app->session->setNotice(Craft::t('sp', count($managers) . ' managers user company updated'));
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function dataResetManagersUserCompany()
    {
        Lantra::$app->settings->resetDataClean('ManagersUserCompany');
        Craft::$app->session->setNotice('All managers have been reset.');
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    private function setResultCache()
    {
        $users = $this->getUsers(500, 'ResultCache', false);
        $message = '';
        foreach ($users as $user) {
            $results = $this->getUserUnitResults($user->id);
            if ($results->count()) {
                Lantra::$app->results->saveUserResultCache($user->id, $results->find());
            }
            $user->setFieldValue('dataCleanResultCache', 1);
            $user->setFieldValue('userType', Lantra::$app->users->canManage($user) ? 'manager' : 'member');
            if (!Craft::$app->elements->saveElement($user, false)) {
                $message .= ' ' . $user->fullName . ' not updated.';
            };
        }
        Craft::$app->session->setNotice(Craft::t('sp', count($users) . ' users results cached. ' . $message));
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function dataResetResultCache()
    {
        Lantra::$app->settings->resetDataClean('ResultCache');
        Craft::$app->session->setNotice('All users have been reset.');
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function setReportIncludeRequired()
    {
        ## update existing reports
        $criteria = Entry::find();
        $criteria->section = 'reports';
        $criteria->limit = null;
        $criteria->status = null;
        foreach ($criteria->all() as $report) {
            $report->setFieldValue('reportIncludeRequired', 1);
            Craft::$app->elements->saveElement($report, false);
        };
        Craft::$app->session->setNotice($criteria->count() . ' reports updated.');
        $this->redirectToPostedUrl();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function copyNotes()
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->limit = 1000;
        $criteria->type = ['unitResult', 'userResult'];
        $criteria->resultNotes = ':notempty:';
        $criteria->status = null;
        foreach ($criteria->all() as $result) {
            $comments['new1'] = array(
                'type' => 3,
                'enabled' => true,
                'fields' => [
                    'user' => [$result->authorId],
                    'comment' => $result->resultNotes,
                    'date' => time(),
                    'read' => 1
                ]
            );
            $result->setFieldValue('resultNotes', '');
            $result->setFieldValue('resultComments', $comments);
            Craft::$app->elements->saveElement($result, false);
        }
        if ($criteria->count()) {
            Craft::$app->session->setNotice($criteria->count() . ' result updated. ');
        } else {
            Craft::$app->session->setError('No results to update. ');
        }
        $this->redirectToPostedUrl();
    }

    /**
     *
     */
    private function copyDatabaseProd()
    {
        $this->copyDatabase('prod');
    }

    /**
     * @param null $target
     * @throws \yii\web\BadRequestHttpException
     */
    private function copyDatabase($target = null)
    {
        if (is_null($target)) {
            $server = Craft::getAlias('server');
            if ($server == 'prod' || $server == 'dev' || $server == 'local') {
                $target = 'uat';
            } else {
                $target = 'dev';
            }
        }

        $result = Lantra::$app->deploy->copyDatabase($target);
        $message = Lantra::$app->deploy->message;
        if ($result) {
            Craft::$app->session->setNotice($message);
        } else {
            Craft::$app->session->setError($message);
        }
        $this->redirectToPostedUrl();
    }
}