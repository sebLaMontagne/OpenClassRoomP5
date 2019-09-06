<?php
class ViewModelDiet extends ViewModel
{
    public $baseCalories;
    public $dietRestrictions;

    public $mainDishList;
    public $starterDishList;
    public $dessertDishList;

    public $dishes;
    public $starters;
    public $desserts;
    
    private $dishManager;

    private function setNormalCals()
    {
        if($_POST['user_gender'] == 'male')
        {
            $this->baseCalories = 2500;
        }
        elseif($_POST['user_gender'] == 'female')
        {
            $this->baseCalories = 2000;
        }
    }
    private function setLowCals()
    {
        $this->baseCalories = 800;
    }

    private function setNoRestriction()
    {
        $this->dietRestrictions['Poultry']      = 1;
        $this->dietRestrictions['Fish']         = 1;
        $this->dietRestrictions['Seafood']      = 1;
        $this->dietRestrictions['FromAnimal']   = 1;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }
    private function setVegetarianRestrictions()
    {
        $this->dietRestrictions['Poultry']      = 0;
        $this->dietRestrictions['Fish']         = 0;
        $this->dietRestrictions['Seafood']      = 0;
        $this->dietRestrictions['FromAnimal']   = 1;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }
    private function setVeganRestrictions()
    {
        $this->dietRestrictions['Poultry']      = 0;
        $this->dietRestrictions['Fish']         = 0;
        $this->dietRestrictions['Seafood']      = 0;
        $this->dietRestrictions['FromAnimal']   = 0;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }
    private function setPescoVegetarianRestrictions()
    {
        $this->dietRestrictions['Poultry']      = 0;
        $this->dietRestrictions['Fish']         = 1;
        $this->dietRestrictions['Seafood']      = 0;
        $this->dietRestrictions['FromAnimal']   = 1;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }
    private function setPollotarianRestrictions()
    {
        $this->dietRestrictions['Poultry']      = 1;
        $this->dietRestrictions['Fish']         = 0;
        $this->dietRestrictions['Seafood']      = 0;
        $this->dietRestrictions['FromAnimal']   = 1;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }

    // On récupère tous les plats possibles compte tenu du régime choisi par l'utilisateur, et on les range
    // dans 3 tableaux ; un pour les plats, un pour les entrées, un pour les dessers

    private function getAllDishes() 
    {
        $this->mainDishList = $this->dishManager->getAllDishesByType('mainDish-a');
        $this->starterDishList = $this->dishManager->getAllDishesByType('starter');
        $this->dessertDishList = $this->dishManager->getAllDishesByType('dessert');

        // la boucle s'arrête quand elle vaut false 
        // remplacer do-while par while et mettre une limite d'intération pour empêcher la page de tourner à l'infini

        for($i=0; $i < 7; $i++)
        {
            // création du tableau des plats
            do
            {
                $index = rand(0, (count($this->mainDishList)-1));
                $this->dishes[$i] = $this->mainDishList[$index];
                $limitCal = $this->baseCalories;

            }while(!$this->isDishValid($this->dishes[$i]) && !($limitCal > $this->dishes[$i]->Calories()));

            // création du tableau des entrées prenant en compte les cals restantes
            do
            {
                $index = rand(0, (count($this->starterDishList)-1));
                $this->starters[$i] = $this->starterDishList[$index];
                $limitCal = $this->baseCalories - $this->dishes[$i]->Calories();

            }while(!$this->isDishValid($this->starters[$i]) && !($limitCal > $this->starters[$i]->Calories()));



            // on ajoute un dessert que si on a au moins 300 calories de libres
            if($this->baseCalories - $this->dishes[$i]->Calories() - $this->starters[$i]->Calories() > 300)
            {
                // création du tableau des désserts prenant en compte les cals restantes +/- 100
                do
                {
                    $index = rand(0, (count($this->dessertDishList)-1));
                    $this->desserts[$i] = $this->dessertDishList[$index];
                    $limitCal = $this->baseCalories - $this->dishes[$i]->Calories() - $this->starters[$i]->Calories();

                    var_dump($limitCal, $this->desserts[$i]->Calories());
                    var_dump($limitCal + 100 > $this->desserts[$i]->Calories());
                    var_dump($limitCal - 100 < $this->desserts[$i]->Calories());

                }while(!$this->isDishValid($this->desserts[$i]) && !(($limitCal + 100 > $this->desserts[$i]->Calories()) && ($limitCal - 100 < $this->desserts[$i]->Calories())));
            }
        }
    }

    // Vérifie qu'un plat respecte bien le type de régime choisi
    private function isDishValid(Dish $dish)
    {
        $isValid = true;

        if(
            ($dish->IsPoultry() == 1 && $this->dietRestrictions['Poultry']   == 0) ||
            ($dish->IsPoultry() == 1 && $this->dietRestrictions['Fish']      == 0) ||
            ($dish->IsPoultry() == 1 && $this->dietRestrictions['Seafood']   == 0) ||
            ($dish->IsPoultry() == 1 && $this->dietRestrictions['FromAnimal']== 0) ||
            ($dish->IsPoultry() == 1 && $this->dietRestrictions['Fruit']     == 0) ||
            ($dish->IsPoultry() == 1 && $this->dietRestrictions['Vegetable'] == 0)
        ){
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
                $this->setNormalCals();
                $this->setNoRestriction();
            break;

            case 'vegetarian':
                $this->setNormalCals();
                $this->setVegetarianRestrictions();
            break;

            case 'vegan':
                $this->setNormalCals();
                $this->setVeganRestrictions();
            break;

            case 'pesco-vegetarian':
                $this->setNormalCals();
                $this->setPescoVegetarianRestrictions();
            break;

            case 'pollotarian':
                $this->setNormalCals();
                $this->setPollotarianRestrictions();
            break;

            case 'low_calories':
                $this->setLowCals();
                $this->setNoRestriction();
            break;
        }

        //Construction de la liste des entrées, des plats et des desserts
        $this->getAllDishes();
    }
}

//  Déterminer les restrictions en fonction du type de régime sélectionné et le sexe (cals/types de plats)
//  créer un template unique (pour afficher la semaine, on retirera les 2 barres latérales pour plus de places)
//      1(vide) - 1(détail) - 1(vide) - 7(semaine) - 2(vide)
//  construire une journée type prenant en compte les restrictions (on prend une entrée(bonus 1), un plat, un dessert (bonus 2))
//      ajouter les id des plats dans un tableau de restrictions supplémentaire (pour éviter que le même plat apparaisse plus d'une fois)
//  répéter l'étape 3 + 4 6 fois (1 pour chaque jour de la semaine)
//  en passant la souris sur un plat, l'utilisateur aura une option 'voir détails' qui affichera le plat détaillé dans une zone de l'écran
//  en passant la souris sur un plat, l'utilisateur aura une option 'reroll plat' qui affichera un nouveau plat aléatoire
//  mettre 2 liens en haut de page : 'retour vers le site' + 'exporter au format pdf' 