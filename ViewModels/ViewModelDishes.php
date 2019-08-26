<?php

class ViewModelDishes extends ViewModel
{
    private $dishManager;
    public $_dishes;

    public function getAllDishes()
    {
        $this->_dishes = $this->dishManager->getAllPublishedDishes();
    }
    public function __construct()
    {
        parent::__construct();
        $this->_title   = $this->_strings['DISHES_TITLE'];
        $this->_view    = 'Views/dishes.html';
        $this->dishManager = new DishManager;
    }
}