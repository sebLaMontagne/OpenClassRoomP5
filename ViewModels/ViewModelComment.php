<?php
class ViewModelComment extends ViewModel
{
    private $commentManager;
    public $comments;

    public function saveArticleComment($localArticleId, $userId, $content)
    {
        $this->commentManager->saveArticleComment($localArticleId, $userId, $content);
    }

    public function saveDishComment($localArticleId, $userId, $content)
    {
        $this->commentManager->saveDishComment($localArticleId, $userId, $content);
    }

    public function getAllContents()
    {
        $this->comments = $this->commentManager->getAllComments();
    }

    public function confirmArticleComment($id)
    {
        $this->commentManager->acceptArticleComment($id);
    }

    public function confirmDishComment($id)
    {
        $this->commentManager->acceptDishComment($id);
    }

    public function removeDishComment($id)
    {
        $this->commentManager->removeDishComment($id);
    }

    public function removeArticleComment($id)
    {
        $this->commentManager->removeArticleComment($id);
    }

    public function __construct()
    {
        parent::__construct();

        $this->_title   = $this->_strings['BACKCOMMENT_TITLE'];
        $this->_view    = 'Views/backComments.html';

        $this->commentManager = new CommentManager;
    }
}