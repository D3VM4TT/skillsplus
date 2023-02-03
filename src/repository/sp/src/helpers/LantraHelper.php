<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\helpers;

use Craft;
use craft\elements\Asset;
use craft\elements\Entry;
use craft\elements\MatrixBlock;
use craft\elements\User;
use craft\helpers\ElementHelper;
use craft\models\VolumeFolder;
use craft\web\View;
use lantra\sp\models\Cycle;
use lantra\sp\models\Record;
use craft\helpers\Assets as AssetsHelper;
use yii\web\UploadedFile;
use Yii;
use verbb\supertable\services\SuperTableService;

use lantra\sp\Plugin as Lantra;

class LantraHelper
{
    /**
     * @param $resultEntry
     * @return null
     */
    public static function certificateUrl($entry = null)
    {
        if (! $entry) {
            return '';
        }
        if ($entry->section->handle == 'packages' && (!$entry->moduleGroup || !$entry->moduleGroup->certificate || $entry->packageStatus != 'complete')) {
            return null;
        }
        elseif ($entry->type == 'unitResult' && (!$entry->resultUnit || !$entry->resultUnit->one()->certificate || $entry->resultStatus != 'endorsed')) {
            return null;
        }
        elseif ($entry->type == 'moduleResult' && (!$entry->resultModule || !$entry->resultModule->one()->moduleGroup->one()->certificate || $entry->resultStatus != 'complete')) {
            return null;
        }
        elseif ($entry->type == 'userResult') {
            return null;
        }
        return '/public/certificate/' . $entry->section->handle . '/' . $entry->author->id . '/' . $entry->id;
    }

    /**
     * @param $handle
     * @return null
     */
    public static function userGroupId($handle)
    {
        $group = Craft::$app->userGroups->getGroupByHandle($handle);
        return $group ? $group->id : null;
    }

    /**
     * @param string $type
     * @return array
     */
    public static function userGroupIds($type = 'users')
    {
        $companyManagersId = self::userGroupId('companyManagers');
        $teamManagersId = self::userGroupId('teamManagers');
        $userIds = [$teamManagersId, $companyManagersId];
        if ($type == 'users') {
            $userIds[] = self::userGroupId('users');
        }
        return $userIds;
    }

    /**
     * @param $user
     * @return string
     */
    public static function userRoles($user)
    {
        if (!count($user->userRole)) {
            return '';
        }
        $roles = [];
        foreach ($user->userRole as $role) {
            $roles[] = $role->title;
        }

        return implode (', ', $roles);
    }

    private static $_sections;
    private static $_groups;

    /**
     * @param $handle
     * @param string $property
     * @return mixed|null
     */
    private static function sectionProperty($handle, $property = 'id')
    {
        if (!self::$_sections) {
            $sections = Craft::$app->sections->getAllSections();
            foreach($sections as $section) {
                self::$_sections[$section->handle] = $section;
            }
        }

        return isset(self::$_sections[$handle]) ? self::$_sections[$handle]->$property : null;
    }

    /**
     * @param $handle
     * @param string $property
     * @return mixed|null
     */
    private static function groupProperty($handle, $property = 'id')
    {
        if (!self::$_groups) {
            $groups = Craft::$app->categories->getAllGroups();
            foreach($groups as $group) {
                self::$_groups[$group->handle] = $group;
            }
        }

        return isset(self::$_groups[$handle]) ? self::$_groups[$handle]->$property : null;
    }

    /**
     * @param $handle
     * @return null
     */
    public static function sectionId($handle)
    {
        return self::sectionProperty($handle, 'id');
    }

    /**
     * @param $handle
     * @return null
     */
    public static function sectionUid($handle)
    {
        return self::sectionProperty($handle, 'uid');
    }

    /**
     * @param $handle
     * @return null
     */
    public static function groupId($handle)
    {
        return self::groupProperty($handle, 'id');
    }

    /**
     * @param $handle
     * @return null
     */
    public static function groupUid($handle)
    {
        return self::groupProperty($handle, 'uid');
    }

    /**
     * @param $handle
     * @return null
     */
    public static function groupStructureId($handle)
    {
        return self::groupProperty($handle, 'structureId');
    }

    /**
     * @param $handle
     * @param $typeHandle
     * @return int|null
     */
    public static function entryTypeId($handle, $typeHandle = null)
    {
        if (null == $section = Craft::$app->sections->getSectionByHandle($handle)) {
            return null;
        }
        $entryTypes = Craft::$app->sections->getEntryTypesBySectionId($section->id);
        if (count($entryTypes) == 1) {
            return $entryTypes[0]->id;
        }
        foreach ($entryTypes as $entryType) {
            if ($entryType->handle == $typeHandle) {
                return $entryType->id;
            }
        }
        return null;
    }

    /**
     * @return string
     */
    public static function returnRef($url = null)
    {
        if (!$url) {
            $url = Craft::$app->request->getReferrer();
        }
        // strip out existing ref
        $url = strtok($url, '?');
        // append ref
        if (false != $ref = Craft::$app->request->getParam('ref')) {
            $url .= '?ref=' . $ref;
        }
        return $url;
    }

    /**
     * @param $field
     * @return null
     */
    public static function userProfileField($field)
    {
        $fields = LantraHelper::setting('userProfileFields');
        if ($fields) {
            foreach ($fields as $row) {
                if ($row['field'] == $field) {
                    return $row;
                }
            }
        }
        return null;
    }

    /**
     * @return bool
     */
    public static function enableBase()
    {
        return LantraHelper::setting('enableBase') === 1;
    }

    /**
     * @param $userId
     * @param $packageId
     * @return string
     */
    public static function packageUrl($userId, $packageId = null)
    {
        if (LantraHelper::setting('themeMyTaskbooks')) {
            return '/cpd/' . $userId . '/taskbooks/' . $packageId;
        }
        if (LantraHelper::setting('themeMyDashboard')) {
            return '/cpd/' . $userId;
        }
        return '/';
    }

    /**
     * @param $owner
     * @param $payerEmail
     * @param $paymentAmount
     * @param $transactionId
     * @return null
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public static function addUserPayment($owner, $payerEmail, $paymentAmount, $transactionId)
    {
        ## stop duplicates
        foreach($owner->userPayments as $block) {
            if ($block->txn_id == $transactionId) {
                return null;
            }
        }
        $field = Craft::$app->fields->getFieldByHandle('userPayments');
        $blockType = Craft::$app->matrix->getBlockTypesByFieldId($field->id)[0];
        ## create payment block
        $payment = new MatrixBlock();
        $payment->fieldId = $field->id;
        $payment->typeId = $blockType->id;
        $payment->ownerId = $owner->id;
        $payment->setAttributes([
            'payer_email'   => $payerEmail,
            'mc_gross'      => $paymentAmount,
            'txn_id'        => $transactionId,
        ]);
        Craft::$app->elements->saveElement($payment);
    }

    /**
     * @param $template
     * @param $variables
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     */
    public static function renderCpTemplate($template, $variables)
    {
        $oldMode = Craft::$app->view->getTemplateMode();
        Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_CP);
        $html = Craft::$app->view->renderTemplate($template, $variables);
        Craft::$app->view->setTemplateMode($oldMode);
        return $html;
    }

    /**
     * @param $userId
     * @return Record|null
     */
    public static function getRecord($userId = null)
    {
        if (null == $user = self::getUser($userId)) {
            return null;
        }
        return new Record($user);
    }

    /**
     * @param $handle
     * @return SuperTableBlockTypeModel|null
     */
    public static function spBlockType($handle)
    {
        if (null == $field = Craft::$app->fields->getFieldByHandle($handle)) {
            return null;
        }
        $sp = new SuperTableService();
        $blockType = $sp->getBlockTypesByFieldId($field->id);
        return $blockType ? $blockType[0] : null;
    }

    /**
     *
     * @return string
     */
    public static function getRelease()
    {
        $release = file_get_contents(CRAFT_BASE_PATH . '/config/.release');
        return $release ? $release : 'unknown';
    }

    /**
     * @param string $key
     * @param string $default
     * @return mixed|null
     */
    public static function setting($key = '', $default = null)
    {
        return Lantra::$app->settings->getSetting($key, $default);
    }

    /**
     * @param $name
     * @return string
     * @throws \yii\base\Exception
     */
    public static function tempFilePath($name)
    {
        if ($file = UploadedFile::getInstanceByName($name)) {
            $destination = AssetsHelper::tempFilePath($file->getExtension());
            move_uploaded_file($file->tempName, $destination);
            return $destination;
        }
        return false;
    }

    /**
     * @param string $volumeHandle
     * @param string $folderId
     * @param $filename
     * @return array|\craft\base\ElementInterface|Asset|null
     */
    public static function findAsset($volumeHandle, $folderId, $filename)
    {
        $filenameSpaces = str_replace('%20', ' ', $filename);
        $filename20 = str_replace('%20', '20', $filename);
        $filenameDash = str_replace(' ', '-', $filename);
        return Asset::find()
            ->volume($volumeHandle)
            ->folderId($folderId)
            ->filename([$filename, $filenameSpaces, $filenameDash, $filename20])
            ->one();
    }

    /**
     * @param $filePath
     * @param $fileName
     * @param string $volumeHandle
     * @param string $folderName
     * @return array
     */
    public static function addAsset($filePath, $fileName, $volumeHandle = 'data', $folderName = '')
    {
        $response = ['asset' => false, 'message' => ''];
        $volume = Craft::$app->volumes->getVolumeByHandle($volumeHandle);
        if (!$volume) {
            $response['message'] = 'Volume not found.';
            return $response;
        }
        if ($folderName) {
            $folder = Craft::$app->assets->findFolder(['volumeId' => $volume->id, 'name' => $folderName]);
        }
        else {
            $folder = Craft::$app->assets->getRootFolderByVolumeId($volume->id);
        }
        if (!$folder) {
            $response['message'] = 'Folder not found.';
            return $response;
        }
        try {
            $asset = new Asset();
            $asset->tempFilePath = $filePath;
            $asset->filename = $fileName;
            $asset->newFolderId = $folder->id;
            $asset->volumeId = $folder->volumeId;
            $asset->avoidFilenameConflicts = true;
            $asset->setScenario(Asset::SCENARIO_CREATE);
            if (null != $uploader = self::getUser()) {
                $asset->uploaderId = $uploader->id;
            }
            if (Craft::$app->getElements()->saveElement($asset)) {
                $response['asset'] = $asset;
            }
        } catch (\Throwable $exception) {
            $response['message'] = 'LantraHelper::addAsset() exception: ' . $exception->getMessage();
        }
        return $response;
    }

    /**
     * @param Asset $asset
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function assetPath(Asset $asset)
    {
        $volumePath = Yii::getAlias($asset->getVolume()->settings['path']);
        $folderPath = $asset->getFolder()->path;
        $volumePath = rtrim($volumePath, '/') . '/';
        $folderPath = rtrim($folderPath, '/') . '/';
        return $volumePath . $folderPath . $asset->filename;
    }

    /**
     * @param $user
     * @return VolumeFolder|null
     * @throws \craft\errors\AssetConflictException
     * @throws \craft\errors\VolumeObjectExistsException
     */
    public static function userEvidenceFolder($user, $volume = 'evidence')
    {
        $volume = Craft::$app->volumes->getVolumeByHandle($volume);
        $parentFolder = Craft::$app->assets->getRootFolderByVolumeId($volume->id);
        $folder = Craft::$app->assets->findFolder(['parentId' => $parentFolder->id, 'name' => $user->id]);
        if (!$folder) {
            $folder = new VolumeFolder();
            $folder->name = $user->id;
            $folder->volumeId = $volume->id;
            $folder->parentId = $parentFolder->id;
            $folder->path = rtrim($folder->name, '/') . '/';
            Craft::$app->assets->createFolder($folder, true);
        }
        return $folder;
    }

    /**
     * @param string $kind
     * @return mixed
     */
    public static function assetIcon($kind = '')
    {
        $icons = [
            'access' => 'fa-file',
            'audio' => 'fa-file-audio',
            'compressed' => 'fa-file-archive',
            'excel' => 'fa-file-excel',
            'html' => 'fa-file-code',
            'illustrator'  => 'fa-file-image',
            'image' => 'fa-file-image',
            'javascript'  => 'fa-file-code',
            'json' => 'fa-file-code',
            'pdf' => 'fa-file-pdf',
            'photoshop' => 'fa-file-image',
            'php' => 'fa-file-code',
            'powerpoint' => 'fa-file-powerpoint',
            'text' => 'fa-file-alt',
            'video' => 'fa-file-video',
            'word' => 'fa-file-word',
            'xml' => 'fa-file',
            'unknown' => 'fa-file'
        ];

        return $kind && isset($icons[$kind]) ? $icons[$kind] : $icons['unknown'];
    }

    /**
     * Generic get user from user id or currently logged in user
     *
     * @param null $userId
     * @return User|null
     */
    public static function getUser($userId = null)
    {
        if (is_object($userId)) {
            return $userId;
        }

        if (is_null($userId)) {
            $userId = Craft::$app->getUser()->id;
        }

        return (int) $userId > 0 ? Craft::$app->users->getUserById( (int) $userId) : null;
    }

    /**
     * @param null $user
     * @return null
     */
    public static function getMembership($user = null)
    {
        if (null == $user = self::getUser($user)) {
            return null;
        }
        $jobRole = $user->userRole->one();
        $membershipOptions = self::setting('membershipOptions');
        foreach ($membershipOptions as $row) {
            if ($row['jobRole'] == $jobRole->id) {
                $row['title'] = $jobRole->title;
                return $row;
            }
        }
        return null;
    }

    /**
     * @param $user
     * @param $field
     * @return mixed
     */
    public static function primaryUserField($user, $field)
    {
        return $user->primaryUser->$field;
    }

    public static function taskbookLevels()
    {

    }

    /**
     * @param $handle
     * @return string|null
     */
    public static function getFieldColumn($handle)
    {
        if (null == $field = Craft::$app->getFields()->getFieldByHandle($handle)) {
            return '';
        }
        return ElementHelper::fieldColumnFromField($field);
    }
}