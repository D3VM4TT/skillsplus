<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;
use craft\errors\AssetException;
use yii\web\HttpException;

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
        $fileId = Craft::$app->request->getParam('fileId');
        $asset = Craft::$app->assets->getAssetById($fileId);
        if(!$asset) {
            $response = [
                'success' => false,
                'message' => 'Invalid asset [' . $fileId . ']'
            ];
            return $this->asJson($response);
        }
        $response = ['success' => true, 'message' => ''];
        try {
            Craft::$app->getElements()->deleteElement($asset);
        } catch (AssetException $exception) {
            $response = [
                'success' => false,
                'message' => $exception->getMessage()
            ];
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
        if (!$folder) {
            $response = [
                'success' => false,
                'message' => 'Invalid folder, contact support.'
            ];
            return $this->asJson($response);
        }
        ## upload file
        $tempFolder = rtrim(Craft::$app->path->tempPath, '/') . '/';
        $tempPath = $tempFolder . $fileName;
        move_uploaded_file($tmpName, $tempPath);
        ## create asset
        $response = LantraHelper::addAsset($tempPath, $fileName, 'evidence', $folder->name);
        ## delete the temp file
        @unlink($tempPath);
        if (is_object($response['asset'])) {
            $response['success'] = true;
        }
        $this->asJson($response);
    }

    /**
     * @param $assetId
     * @throws HttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionInternal($assetId)
    {
        $this->requireLogin();
        if (false == $asset = Craft::$app->assets->getAssetById($assetId)) {
            throw new HttpException(404, "Sorry. File not found or permission denied.");
            return;
        }

        $volumePath = rtrim($asset->getVolume()->settings['path'], '/') . '/';
        $folderPath =  rtrim($asset->getFolder()->path, '/') . '/';
        $assetFilePath = Craft::getAlias($volumePath) . $folderPath . $asset->filename;

        Craft::$app->response->sendFile($assetFilePath);
    }
}