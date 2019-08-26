<?php
class ViewModelArticleTrad extends ViewModel
{
    private $articleManager;
    public $referenceArticle;
    public $isSaved;

    public function saveArticleTrad($lang, $articleId, $title, $content)
    {
        $this->articleManager->saveArticleTrad($lang, $articleId, $title, $content);
        $this->isSaved = true;
    }

    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['ARTICLE_TRAD_TITLE'];
        $this->_view = 'Views/articleTrad.html';

        $this->articleManager = new ArticleManager;

        $this->referenceArticle = $this->articleManager->getArticleVersion($_GET['id'], $_GET['baseLang']);
        $this->isSaved = false;
    }
}