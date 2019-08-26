<?php

class ViewModelTraductor extends ViewModel
{
    private $articleManager;
    private $dishManager;
    private $ingredientManager;
    private $categoryManager;

    public $articlesTrads;
    public $dishesTrads;
    public $ingredientsTrads;
    public $categoriesTrads;

    private function getUntraductedArticles()
    {
        $i = 0;
        $articles = $this->articleManager->getSimplifiedArticles();

        foreach($articles as $article)
        {
            foreach($this->_languages as $language)
            {
                if(!$this->articleManager->doArticleVersionExists($article['id'], $language))
                {
                    $this->articlesTrads[$i]['article'] = $this->articleManager->getArticleBaseVersion($article['id']);
                    $this->articlesTrads[$i]['lang'] = $language;
                }
            }
            $i++;
        }
    }

    private function getUntraductedDishes()
    {
        $i = 0;
        $dishes = $this->dishManager->getSimplifiedDishes();

        foreach($dishes as $dish)
        {
            foreach($this->_languages as $language)
            {
                if(!$this->dishManager->doDishVersionExists($dish['id'], $language))
                {
                    $this->dishesTrads[$i]['dish'] = $this->dishManager->getDishBaseVersion($dish['id']);
                    $this->dishesTrads[$i]['lang'] = $language;
                }
            }
            $i++;
        }
    }

    private function getUntraductedIngredients()
    {
        $i = 0;
        $ingredients = $this->ingredientManager->getSimplifiedIngredients();

        foreach($ingredients as $ingredient)
        {
            foreach($this->_languages as $language)
            {
                if(!$this->ingredientManager->doIngredientVersionExists($ingredient['id'], $language))
                {
                    $this->ingredientsTrads[$i]['ingredient'] = $this->ingredientManager->getIngredientBaseVersion($ingredient['id']);
                    $this->ingredientsTrads[$i]['lang'] = $language;
                }
            }
            $i++;
        }
    }

    private function getUntraductedCategories()
    {
        $i = 0;
        $categories = $this->categoryManager->getSimplifiedCategories();

        foreach($categories as $category)
        {
            foreach($this->_languages as $language)
            {
                if(!$this->categoryManager->doCategoryVersionExists($category['id'], $language))
                {
                    $this->categoriesTrads[$i]['category'] = $this->categoryManager->getCategoryBaseVersion($category['id']);
                    $this->categoriesTrads[$i]['lang'] = $language;
                }
            }
            $i++;
        }
    }

    public function __construct()
    {
        include('constantes.php');
        $this->_languages = $LANGUAGES;

        parent::__construct();

        $this->_view = 'Views/traductor.html';
        $this->_title = $this->_strings['TRADUCTOR_TITLE'];

        $this->articleManager = new ArticleManager;
        $this->dishManager = new DishManager;
        $this->ingredientManager = new IngredientManager;
        $this->categoryManager = new CategoryManager;

        $this->getUntraductedArticles();
        $this->getUntraductedDishes();
        $this->getUntraductedIngredients();
        $this->getUntraductedCategories();
    }
}