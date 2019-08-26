<?php
class ViewModelCategoryTrad extends ViewModel
{
    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['CATEGORY_TRAD_TITLE'];
        $this->_view = 'Views/categoryTrad.html';
    }
}