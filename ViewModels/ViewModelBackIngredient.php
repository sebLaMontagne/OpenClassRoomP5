<?php

class ViewModelBackIngredient extends ViewModel
{
    private $ingredientManager;

    public function confirmIngredient($id)
    {
        $this->ingredientManager->confirmIngredient($id);
    }

    public function refuseIngredient($id)
    {
        $this->ingredientManager->deleteIngredient($id);
    }

    public function __construct()
    {
        $this->ingredientManager = new IngredientManager;
    }
}