<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;
use craft\errors\VolumeException;

use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;

class AssetsController extends BaseController
{

    public $allowAnonymous = array(
        'actionUploadEvidence',
        'actionDeleteEvidence'
    );

    /**
     * Uploads evidence from front end
     *
     * @throws mixed
     */
    public function actionDeleteEvidence()
    {
        $this->requireAcceptsJson();
        $fileId = Craft::$app->request->getParam('fileId');
        $asset = Craft::$app->assets->getAssetById($fileId);
        $volume = $asset->getVolume();
        $response = ['success' => true, 'message' => ''];
        try {
            $volume->deleteFile($asset->folderPath . $asset->filename);
        } catch(VolumeException $exception) {
            $response['success'] = false;
            $response['message'] = $exception->getMessage();

        }
        $this->asJson($response);
    }

    /**
     * Uploads evidence from front end
     *
     * @throws mixed
     */
    public function actionUploadEvidence()
    {
        $this->requireAcceptsJson();
        if (empty($_FILES) || !isset($_FILES['assets-upload']) || !isset($_FILES['assets-upload']['name']) || !isset($_FILES['assets-upload']['tmp_name'])) {
            $response = [
                'success' => false,
                'message' => 'Invalid upload parameters. Refresh and try again.'
            ];
            return $this->asJson($response);
        }
        $fileName = $_FILES['assets-upload']['name'];
        $tmpName = $_FILES['assets-upload']['tmp_name'];
        ## get folder
        $folderId = Craft::$app->request->getParam('folderId');
        $folder = Craft::$app->assets->getFolderById($folderId);
        ## upload file
        $tempFolder = Craft::$app->path->tempPath;
        $tempPath = $tempFolder . $fileName;
        move_uploaded_file($tmpName, $tempPath);
        ## create asset
        $response = LantraHelper::addAsset($tempPath, $fileName, 'evidence', $folder->name);
        ## delete the temp file
        unlink($tempPath);
        if ($response['asset']) {
            $response['file'] = $response['asset']->id;
        }
        $this->asJson($response);
    }
}