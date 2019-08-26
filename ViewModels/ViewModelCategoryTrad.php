<?php
class ViewModelCategoryTrad extends ViewModel
{
    private $categoryManager;
    public $referenceCategory;

    public function saveCategoryTrad($lang, $category_id, $name)
    {
        $this->categoryManager->saveCategoryTrad($lang, $category_id, $name);
    }

    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['CATEGORY_TRAD_TITLE'];
        $this->_view = 'Views/categoryTrad.html';

        $this->categoryManager = new CategoryManager;

        $this->referenceCategory = $this->categoryManager->getCategoryBaseVersion($_GET['id']);
    }
}