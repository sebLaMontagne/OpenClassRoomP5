<?php

class ViewModelBackArticle extends ViewModel
{
    private $articleManager;

    public function getArticle($id)
    {
        $this->_article = $this->articleManager->getUnpublishedArticle($id);
    }

    public function confirmArticle($id)
    {
        $this->articleManager->confirmArticle($id);
    }

    public function refuseArticle($id)
    {
        $this->articleManager->refuseArticle($id);
    }

    public function __construct()
    {
        parent::__construct();
        $this->_view = 'Views/backArticle.html';
        $this->_title = $this->_strings['BACKARTICLE_TITLE'];

        $this->articleManager = new ArticleManager;
    }
}