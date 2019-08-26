<?php

class ViewModelSearch extends ViewModel
{
    private $dishManager;
    public $_searchResults;

    public function getResults()
    {
        $this->_searchResults = $this->dishManager->searchDish($_POST['search']);
    }

    public function __construct()
    {
        parent::__construct();
        $this->_title       =   $this->_strings['SEARCH_TITLE'];
        $this->_view        =   'Views/search.html';

        $this->dishManager = new DishManager;
    }
}