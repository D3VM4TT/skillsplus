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
use craft\models\VolumeFolder;
use craft\helpers\Assets as AssetsHelper;
use yii\web\UploadedFile;

use lantra\sp\Plugin as Lantra;

class LantraHelper
{
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
     * @return mixed|null
     */
    public static function setting($key = '')
    {
        return Lantra::$app->settings->getSetting($key);
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
        $folder = $folderName ? Craft::$app->assets->findFolder(['handle' => $folderName]) : Craft::$app->assets->getRootFolderByVolumeId($volume->id);
        if (!$folder) {
            $response['message'] = 'Folder not found.';
            return $response;
        }
        try {
            $asset = new Asset();
            $asset->avoidFilenameConflicts = true;
            $asset->setScenario(Asset::SCENARIO_CREATE);
            $asset->tempFilePath = $filePath;
            $asset->filename = $fileName;
            $asset->newFolderId = $folder->id;
            $asset->volumeId = $folder->volumeId;

            $response['asset'] = Craft::$app->getElements()->saveElement($asset);

        } catch (\Throwable $exception) {
            $response['message'] = $exception->getMessage();
        }
        return $response;
    }

    /**
     * @param $user
     * @return VolumeFolder|null
     * @throws \craft\errors\AssetConflictException
     * @throws \craft\errors\VolumeObjectExistsException
     */
    public static function userEvidenceFolder($user)
    {
        $volume = Craft::$app->volumes->getVolumeByHandle('evidence');
        $parentFolder = Craft::$app->assets->getRootFolderByVolumeId($volume->id);
        $folder = Craft::$app->assets->findFolder(['parent' => $parentFolder, 'name' => $user->id]);
        if (!$folder) {
            $folder = new VolumeFolder();
            $folder->name = $user->id;
            $folder->parentId = $parentFolder->id;
            Craft::$app->assets->createFolder($folder, true);
        }
        return $folder;

    }
}