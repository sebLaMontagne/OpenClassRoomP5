<?php 
class ViewModelDishTrad extends ViewModel
{
    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['DISH_TRAD_TITLE'];
        $this->_view = 'Views/dishTrad.html';
    }
}