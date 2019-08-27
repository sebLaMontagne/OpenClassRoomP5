<?php

class ViewModelSearch extends ViewModel
{
    private $dishManager;
    private $articleManager;

    public $_dishesResults;
    public $_articlesResults;
    public $resultsCount;

    public function getResults()
    {
        $this->_dishesResults = $this->dishManager->searchDishes($_POST['search']);
        $this->_articlesResults = $this->articleManager->searchArticles($_POST['search']);

        $this->resultsCount = count($this->_dishesResults) + count($this->_articlesResults);
    }

    public function __construct()
    {
        parent::__construct();
        $this->_title       =   $this->_strings['SEARCH_TITLE'];
        $this->_view        =   'Views/search.html';

        $this->dishManager = new DishManager;
        $this->articleManager = new ArticleManager;
    }
}