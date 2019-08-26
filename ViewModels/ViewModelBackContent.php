<?php

class ViewModelBackContent extends ViewModel
{
    public $_ingredients;
    public $_dishes;
    public $_articles;
    
    private $ingredientManager;
    private $dishManager;
    private $articleManager;

    public function __construct()
    {
        parent::__construct();

        $this->_title = $this->_strings['BACK_CONTENT_TITLE'];
        $this->_view = 'Views/backContent.html';

        $this->ingredientManager = new IngredientManager;
        $this->dishManager = new DishManager;
        $this->articleManager = new ArticleManager;

        $this->_ingredients = $this->ingredientManager->getAllUnpublishedIngredients();
        $this->_dishes = $this->dishManager->getAllUnpublishedDishes();
        $this->_articles = $this->articleManager->getAllUnpublishedArticles();
    }
}