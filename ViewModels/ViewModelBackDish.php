<?php

class ViewModelBackDish extends ViewModel
{
    private $dishManager;

    public function getDish()
    {
        $this->_dish = $this->dishManager->getUnpublishedDish($_GET['id']);
    }
    public function confirmDish($id)
    {
        $this->dishManager->confirmDish($id);
    }

    public function refuseDish($id)
    {
        $this->dishManager->refuseDish($id);
    }

    public function __construct()
    {
        parent::__construct();
        $this->_view = 'Views/backDish.html';
        $this->_title = $this->_strings['BACKDISH_TITLE'];
        $this->dishManager = new DishManager;
    }
}