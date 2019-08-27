<?php

class ViewModelBackContent extends ViewModel
{
    public $_ingredients;
    public $_dishes;
    public $_articles;
    public $_categories;
    
    private $ingredientManager;
    private $dishManager;
    private $articleManager;
    private $categoryManager;

    public function __construct()
    {
        parent::__construct();

        $this->_title = $this->_strings['BACK_CONTENT_TITLE'];
        $this->_view = 'Views/backContent.html';

        $this->ingredientManager = new IngredientManager;
        $this->dishManager = new DishManager;
        $this->articleManager = new ArticleManager;
        $this->categoryManager = new CategoryManager;

        $this->_ingredients = $this->ingredientManager->getAllUnpublishedIngredients();
        $this->_dishes = $this->dishManager->getAllUnpublishedDishes();
        $this->_articles = $this->articleManager->getAllUnpublishedArticles();
        $this->_categories = $this->categoryManager->getAllUnpublishedCategories();
    }
}