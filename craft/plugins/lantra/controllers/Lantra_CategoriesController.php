<?php

namespace Craft;

class Lantra_CategoriesController extends Lantra_BaseController {

    public $allowAnonymous = array('actionDeleteCategory');

    /**
     * Deletes categories from the front end
     *
     * @throws Exception
     */
    public function actionDeleteCategory()
    {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();

        $categoryId = craft()->request->getPost('categoryId');
        if (FALSE == $category = craft()->categories->getCategoryById($categoryId)) {
            $this->_returnError('Invalid category ID.');
        }

        $category->enabled = false;

        if ( ! craft()->categories->saveCategory($category)) {
            $this->_returnError('Error updating category record.');
        }

        $this->_returnMessage('Category has been removed.');
    }
}
