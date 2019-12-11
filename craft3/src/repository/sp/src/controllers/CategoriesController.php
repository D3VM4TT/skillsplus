<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;

use lantra\sp\Plugin as Lantra;

class CategoriesController extends BaseController {

    public $allowAnonymous = array('actionDeleteCategory');

    /**
     * Deletes categories from the front end
     *
     * @throws mixed
     */
    public function actionDeleteCategory(){
        $this->requirePostRequest();
        $this->requireLogin();
        // get the posted categoryId
        $categoryId = Craft::$app->request->getParam('categoryId');
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
