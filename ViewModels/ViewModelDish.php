<?php 

class ViewModelDish extends ViewModel
{
    public $dishManager;
    public $appreciationManager;

    public $_userAppreciation;
    public $_dish;

    public function getUserAppreciation()
    {
        if(!empty($_SESSION['id']))
        {
            return $this->appreciationManager->getDishAppreciation($_SESSION['id'], $_GET['id']);
        }
    }

    public function __construct()
    {
        parent::__construct();

        $this->dishManager          = new DishManager;
        $this->appreciationManager  = new AppreciationManager;

        $this->_userAppreciation    = $this->getUserAppreciation();
        $this->_dish                = $this->dishManager->getPublishedDish($_GET['id']);

        $this->_title   = 'Marre-Mitton - '.$this->_dish->Name();
        $this->_view    = 'Views/dish.html';
    }
}