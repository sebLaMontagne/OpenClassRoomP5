<?php

class ViewModelHome extends ViewModel
{
    public $_recentArticles;
    public $_recentDishes;

    private $dishManager;
    private $articleManager;

    private function getActuality()
    {
        $this->_recentArticles = $this->articleManager->getMostRecentArticles();
        $this->_recentDishes = $this->dishManager->getMostRecentDishes();
    }

    public function __construct()
    {
        parent::__construct();
        $this->_title       =   $this->_strings['HOME_TITLE'];
        $this->_view        =   'Views/home.html';

        $this->dishManager = new DishManager;
        $this->articleManager = new ArticleManager;

        $this->getActuality();
    }
}