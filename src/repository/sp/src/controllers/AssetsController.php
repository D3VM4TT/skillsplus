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
use craft\elements\Asset;
use yii\web\HttpException;

use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;

class AssetsController extends BaseController
{
    public $allowAnonymous = [
        'actionUploadEvidence',
        'actionDeleteEvidence'
    ];

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
        $size = $_FILES['assets-upload']['size'];

        $user = LantraHelper::getUser();

        ## get specific folder
        if (null != $folderId = Craft::$app->request->getParam('folderId')) {
            $evidenceFolder = Craft::$app->assets->getFolderById($folderId);
        }
        ## default to user evidence folder
        else {
            $evidenceFolder = LantraHelper::userEvidenceFolder($user);
        }

        if (!$evidenceFolder) {
            $response = [
                'success' => false,
                'message' => 'Invalid folder, contact support.'
            ];
            return $this->asJson($response);
        }

        ## add some randomness to avoid conflicts
        $uploadId = Craft::$app->request->getParam('uploadId', $user->id);
        $fieldName = Craft::$app->request->getParam('fieldName', 'evidence[]');

        ## upload file
        $tempFolder = rtrim(Craft::$app->path->tempPath, '/') . '/';
        $tempPath = $tempFolder . $uploadId . '-' . $fileName;

        $append = is_file($tempPath);

        if ($append) {
            file_put_contents($tempPath, fopen($tmpName, 'r'),FILE_APPEND);
        }
        else {
            move_uploaded_file($tmpName, $tempPath);
        }

        $headerSize = $this->headerSize();
        $uploadedSize = $this->fileSize($tempPath, $append);
        $isComplete = !$headerSize || $uploadedSize == $headerSize;

        if (!$isComplete) {
            $response = [
                'success' => true,
                'totalSize' => $headerSize,
                'uploadedSize' => $uploadedSize
            ];
            return $this->asJson($response);
        }

        ## create asset
        $response = LantraHelper::addAsset($tempPath, $fileName, 'evidence', $evidenceFolder->name);
        ## delete the temp file
        @unlink($tempPath);
        if (is_object($response['asset'])) {
            $template = '_includes/evidence/asset';
            $response['html'] = Craft::$app->view->renderTemplate($template, ['asset' => $response['asset'], 'fieldName' => $fieldName]);
            $response['success'] = true;
        }
        $this->asJson($response);
    }

    /**
     * @param $filePath
     * @param bool $cache
     * @return float
     */
    protected function fileSize($filePath, $cache = false) {
        if ($cache) {
            clearstatcache(true, $filePath);
        }
        return $this->fixSize(filesize($filePath));
    }

    /**
     * @return int
     */
    protected function headerSize()
    {
        if (!isset($_SERVER['HTTP_CONTENT_RANGE'])) {
          return 0;
        }
        $range = preg_split('/[^0-9]+/', $_SERVER['HTTP_CONTENT_RANGE']);
        return isset($range[3]) ? $this->fixSize($range[3]) : 0;
    }

    /**
     * @param $size
     * @return float
     */
    protected function fixSize($size) {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return $size;
    }

    /**
     * Uploads evidence from front end
     *
     * @throws mixed
     */
    public function actionBrowseEvidence()
    {
        $this->requireLogin();
        $user = LantraHelper::getUser();

        $volume = Craft::$app->request->getParam('volume', 'evidence');
        $fieldName = Craft::$app->request->getParam('fieldName', 'evidence[]');

        $response = [
            'success' => false,
            'message' => '',
            'assets' => []
        ];

        if (null == $evidenceFolder = LantraHelper::userEvidenceFolder($user, $volume)) {
            $response['message'] = 'Could not access evidence folder.';
            return $this->asJson($response);
        }

        $response['success'] = true;
        $response['assets'] = [];

        $assets = Asset::find()
            ->volume($volume)
            ->folderId($evidenceFolder->id)
            ->orderBy('dateCreated DESC')
            ->all();

        $template = '_includes/evidence/asset';

        foreach($assets as $asset) {
            $response['assets'][$asset->id] = [
                'asset' => $asset,
                'html' => Craft::$app->view->renderTemplate($template, ['asset' => $asset, 'fieldName' => $fieldName])
            ];
        }

        return $this->asJson($response);
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
            throw new HttpException(404, "Asset not found or permission denied.");
            return;
        }

        $volumePath = rtrim($asset->getVolume()->settings['path'], '/') . '/';
        $folderPath = rtrim($asset->getFolder()->path, '/') . '/';
        $assetFilePath = Craft::getAlias($volumePath) . $folderPath . $asset->filename;

        if (!is_file($assetFilePath)) {
            throw new HttpException(404, "Asset file does not exist.");
            return;
        }
        Craft::$app->response->sendFile($assetFilePath);
    }

}