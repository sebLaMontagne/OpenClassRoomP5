<?php

class ViewModelArticles extends ViewModel
{
    public $_articles;

    public function getArticles()
    {
        $this->_articles = $this->articleManager->getAllArticles();
    }

    public function __construct()
    {
        parent::__construct();

        $this->_view = 'Views/articles.html';
        $this->_title = $this->_strings['ARTICLES_TITLE'];

        $this->articleManager = new ArticleManager;
    }
}