<?php
class ViewModelArticleTrad extends ViewModel
{
    private $articleManager;
    public $referenceArticle;

    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['ARTICLE_TRAD_TITLE'];
        $this->_view = 'Views/articleTrad.html';

        $this->articleManager = new ArticleManager;

        $this->referenceArticle = $this->articleManager->getPublishedArticle($_GET['id'], $_GET['baseLang']);
    }
}