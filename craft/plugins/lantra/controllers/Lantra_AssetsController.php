<?php

namespace Craft;

class Lantra_AssetsController extends Lantra_BaseController
{

    public $allowAnonymous = array(
        'actionUploadEvidence',
        'actionDeleteEvidence'
    );

    /**
     * @throws Exception
     */
    public function actionSpecialReport() {
        craft()->userSession->requireLogin();
        $type = craft()->request->getParam('type', 'users');
        $manager = craft()->userSession->getUser();
        craft()->lantra_reports->getSpecialReport($manager, $type);
        return;
    }

    /**
     * Uploads evidence from front end
     *
     * @throws mixed
     */
    public function actionDeleteEvidence()
    {
        $this->requireAjaxRequest();
        $fileId = craft()->request->getPost('fileId');
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
        $folderId = craft()->request->getPost('folderId');
        $response = craft()->assets->uploadFile($folderId);
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