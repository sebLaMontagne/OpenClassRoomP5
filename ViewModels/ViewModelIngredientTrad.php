<?php
class ViewModelIngredientTrad extends ViewModel
{
    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['INGREDIENT_TRAD_TITLE'];
        $this->_view = 'Views/ingredientTrad.html';
    }
}