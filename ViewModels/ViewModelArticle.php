<?php

class ViewModelArticle extends ViewModel
{
    private $articleManager;
    private $appreciationManager;

    public $_userAppreciation;
    public $_article;
    
    private function getUserAppreciation()
    {
        if(!empty($_SESSION['id']))
        {
            return $this->appreciationManager->getArticleAppreciation($_SESSION['id'], $_GET['id']);
        }
    }

    public function __construct()
    {
        parent::__construct();
        
        $this->articleManager       = new ArticleManager;
        $this->appreciationManager  = new AppreciationManager;

        $this->_article             = $this->articleManager->getPublishedArticle($_GET['id'], $_GET['lang']);
        $this->_userAppreciation    = $this->getUserAppreciation();

        $this->_view    = 'Views/article.html';
        $this->_title   = 'Marre-mitton - '.$this->_article->Title();
    }
}