<?php
class ViewModelDiets extends ViewModel
{
    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['DIETS_TITLE'];
        $this->_view = 'Views/diets.html';
    }
}