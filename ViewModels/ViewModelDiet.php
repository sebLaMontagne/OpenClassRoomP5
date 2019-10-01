<?php
class ViewModelDiet extends ViewModel
{
    private $dietRestrictions;
    private $dishManager;

    public $JSON_DishList;
    public $JSON_StarterDishList;
    public $JSON_DessertDishList;

    private function setNoRestriction()
    {
        $this->dietRestrictions['Flesh']        = 1;
        $this->dietRestrictions['Poultry']      = 1;
        $this->dietRestrictions['Fish']         = 1;
        $this->dietRestrictions['Seafood']      = 1;
        $this->dietRestrictions['FromAnimal']   = 1;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }
    private function setVegetarianRestrictions()
    {
        $this->dietRestrictions['Flesh']        = 0;
        $this->dietRestrictions['Poultry']      = 0;
        $this->dietRestrictions['Fish']         = 0;
        $this->dietRestrictions['Seafood']      = 0;
        $this->dietRestrictions['FromAnimal']   = 1;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }
    private function setVeganRestrictions()
    {
        $this->dietRestrictions['Flesh']        = 0;
        $this->dietRestrictions['Poultry']      = 0;
        $this->dietRestrictions['Fish']         = 0;
        $this->dietRestrictions['Seafood']      = 0;
        $this->dietRestrictions['FromAnimal']   = 0;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }
    private function setPescoVegetarianRestrictions()
    {
        $this->dietRestrictions['Flesh']        = 0;
        $this->dietRestrictions['Poultry']      = 0;
        $this->dietRestrictions['Fish']         = 1;
        $this->dietRestrictions['Seafood']      = 0;
        $this->dietRestrictions['FromAnimal']   = 1;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }
    private function setPollotarianRestrictions()
    {
        $this->dietRestrictions['Flesh']        = 0;
        $this->dietRestrictions['Poultry']      = 1;
        $this->dietRestrictions['Fish']         = 0;
        $this->dietRestrictions['Seafood']      = 0;
        $this->dietRestrictions['FromAnimal']   = 1;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }

    private function getAllDishes() 
    {
        $dishesList = $this->dishManager->getAllDishesByType('mainDish-a');
        $startersList = $this->dishManager->getAllDishesByType('starter');
        $dessertsList = $this->dishManager->getAllDishesByType('dessert');

        foreach($dishesList as $dish)
        {
            if($this->isDishValid($dish) && count($this->JSON_DishList) < 100)
            {
                $this->JSON_DishList[] = $dish->getJSONdata();
            }
        }
        foreach($startersList as $dish)
        {
            if($this->isDishValid($dish) && count($this->JSON_StarterDishList) < 100)
            {
                $this->JSON_StarterDishList[] = $dish->getJSONdata();
            }
        }
        foreach($dessertsList as $dish)
        {
            if($this->isDishValid($dish) && count($this->JSON_DessertDishList) < 100)
            {
                $this->JSON_DessertDishList[] = $dish->getJSONdata();
            }
        }
    }
    private function isDishValid(Dish $dish)
    {
        $isValid = true;

        if(
            ($dish->IsFlesh()       == 1 && $this->dietRestrictions['Flesh']        == 0) ||
            ($dish->IsPoultry()     == 1 && $this->dietRestrictions['Poultry']      == 0) ||
            ($dish->IsFish()        == 1 && $this->dietRestrictions['Fish']         == 0) ||
            ($dish->IsSeafood()     == 1 && $this->dietRestrictions['Seafood']      == 0) ||
            ($dish->IsFromAnimal()  == 1 && $this->dietRestrictions['FromAnimal']   == 0) ||
            ($dish->IsFruit()       == 1 && $this->dietRestrictions['Fruit']        == 0) ||
            ($dish->IsVegetable()   == 1 && $this->dietRestrictions['Vegetable']    == 0))
        {
            $isValid = false;
        }

        return $isValid;
    }


    public function __construct()
    {
        parent::__construct();
        $this->_template = 'Views/dietTemplate.html';
        $this->_view = 'Views/diet.html';
        $this->_title = $this->_strings['DIET_TITLE'];

        $this->dishManager = new DishManager;

        //détermination des restrictions en fonction du type de régime
        switch($_POST['diet_type'])
        {
            case 'normal':
                $this->setNoRestriction();
            break;

            case 'vegetarian':
                $this->setVegetarianRestrictions();
            break;

            case 'vegan':
                $this->setVeganRestrictions();
            break;

            case 'pesco-vegetarian':
                $this->setPescoVegetarianRestrictions();
            break;

            case 'pollotarian':
                $this->setPollotarianRestrictions();
            break;
        }

        //Construction de la liste des entrées, des plats et des desserts
        $this->getAllDishes();
    }
}