<?php
class ViewModelIngredientTrad extends ViewModel
{
    private $ingredientManager;
    public $referenceIngredient;

    public function saveIngredientTrad($lang, $ingredient_id, $name)
    {   
        $this->ingredientManager->saveIngredientTrad($lang, $ingredient_id, $name);
    }
    
    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['INGREDIENT_TRAD_TITLE'];
        $this->_view = 'Views/ingredientTrad.html';

        $this->ingredientManager = new IngredientManager;

        $this->referenceIngredient = $this->ingredientManager->getIngredientBaseVersion($_GET['id']);
    }
}