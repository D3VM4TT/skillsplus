<?php

namespace Craft;

class Lantra_CategoriesController extends Lantra_BaseController {

    public $allowAnonymous = array('actionDeleteCategory');

    /**
     * Deletes categories from the front end
     *
     * @throws mixed
     */
    public function actionDeleteCategory(){
        $this->requirePostRequest();
        craft()->userSession->requireLogin();
        // get the posted categoryId
        $categoryId = Craft::$app->request->getPost('categoryId');
        if (false == $category = craft()->categories->getCategoryById($categoryId)) {
            $this->_returnError('Invalid category ID.');
        }
        $category->enabled = false;
        // save disabled category
        if ( ! craft()->categories->saveCategory($category)) {
            $this->_returnError('Error removing category.');
        }
        $this->_returnMessage('Category has been removed.');
    }
}
