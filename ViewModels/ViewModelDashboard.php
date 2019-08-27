<?php

class ViewModelDashboard extends ViewModel
{
    private $ingredientManager;
    private $dishManager;
    private $articleManager;
    private $commentManager;
    private $userManager;
    private $messageManager;
    private $categoryManager;

    public $_contentToCheck;
    public $_unapprovedComments;
    public $_users;
    public $_messages;

    public function __construct()
    {
        parent::__construct();
        $this->_title       =   $this->_strings['DASHBOARD_TITLE'];
        $this->_view        =   'Views/dashboard.html';

        $this->ingredientManager    = new IngredientManager;
        $this->dishManager          = new DishManager;
        $this->articleManager       = new ArticleManager;
        $this->commentManager       = new CommentManager;
        $this->userManager          = new UserManager;
        $this->messageManager       = new MessageManager;
        $this->categoryManager      = new CategoryManager;

        $this->_contentToCheck      = count($this->ingredientManager->getAllUnpublishedIngredients());
        $this->_contentToCheck     += count($this->dishManager->getAllUnpublishedDishes());
        $this->_contentToCheck     += count($this->articleManager->getAllUnpublishedArticles());
        $this->_contentToCheck     += count($this->categoryManager->getAllUnpublishedCategories());

        $this->_unapprovedComments  = count($this->commentManager->getAllComments());

        $this->_users       = $this->userManager->countUsers();
        $this->_messages    = count($this->messageManager->getAllMessages());
    }
}