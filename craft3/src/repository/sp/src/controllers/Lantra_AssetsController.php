<?php

namespace Craft;

class Lantra_AssetsController extends Lantra_BaseController
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
        $this->requireAjaxRequest();
        $fileId = Craft::$app->request->getPost('fileId');
        $response = craft()->assets->deleteFiles([$fileId]);
        $this->returnJson($response->getResponseData());
    }

    /**
     * Uploads evidence from front end
     *
     * @throws mixed
     */
    public function actionUploadEvidence()
    {
        $this->requireAjaxRequest();
        $this->requireLogin();

        if (empty($_FILES) || ! isset($_FILES['assets-upload']) || ! isset($_FILES['assets-upload']['name']) || ! isset($_FILES['assets-upload']['tmp_name'])) {
            $data = [
                'success' => false,
                'message' => 'Invalid upload parameters. Refresh and try again.'
            ];

            return $this->returnJson($data);
        }

        $fileName = $_FILES['assets-upload']['name'];
        $tmpName = $_FILES['assets-upload']['tmp_name'];

        $folderId = Craft::$app->request->getPost('folderId');
        $fileLocation = AssetsHelper::getTempFilePath(pathinfo($fileName, PATHINFO_EXTENSION));
        move_uploaded_file($tmpName, $fileLocation);
        $response = craft()->assets->insertFileByLocalPath($fileLocation, $fileName, $folderId, AssetConflictResolution::KeepBoth);
        IOHelper::deleteFile($fileLocation, true);

        if ($response->isError()) {
            $data = [
                'success' => false,
                'message' => $response->getAttribute('errorMessage')
            ];
        }
        if ($response->isConflict()) {
            $data = [
                'success' => false,
                'message' => $response->getDataItem('fileName')  . ' already exists in your folder, please rename your file and try again.'
            ];
        }
        if ($response->isSuccess()) {
            $fileId = $response->getDataItem('fileId');
            $file = craft()->assets->getFileById($fileId);
            $data = [
                'success' => true,
                'file' => $file
            ];
        }
        $this->returnJson($data);
    }
}