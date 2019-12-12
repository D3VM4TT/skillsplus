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
     * @param $filePath
     * @param $fileName
     * @param string $volumeHandle
     * @param string $folderHandle
     * @return array
     */
    public static function addAsset($filePath, $fileName, $volumeHandle = 'data', $folderHandle = '')
    {
        $response = ['asset' => false, 'message' => ''];
        $volume = Craft::$app->volumes->getVolumeByHandle($volumeHandle);
        if (!$volume) {
            $response['message'] = 'Volume not found.';
            return $response;
        }
        $folder = $folderHandle ? Craft::$app->assets->findFolder(['handle' => $folderHandle]) : Craft::$app->assets->getRootFolderByVolumeId($volume->id);
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
}