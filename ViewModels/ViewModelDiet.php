<?php
class ViewModelDiet extends ViewModel
{
    public $baseCalories;
    public $dietRestrictions;

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

    private function setNoRestriction()
    {
        $this->dietRestrictions['Poultry']      = 1;
        $this->dietRestrictions['Fish']         = 1;
        $this->dietRestrictions['Seafood']      = 1;
        $this->dietRestrictions['FromAnimal']   = 1;
        $this->dietRestrictions['Fruit']        = 1;
        $this->dietRestrictions['Vegetable']    = 1;
    }

    public function __construct()
    {
        parent::__construct();
        $this->_template = 'Views/dietTemplate.html';
        $this->_view = 'Views/diet.html';
        $this->_title = $this->_strings['DIET_TITLE'];

        //détermination des restrictions en fonction du type de régime
        switch($_POST['diet_type'])
        {
            case 'normal':
                $this->setNormalCals();
                $this->setNoRestriction();
            break;

            case 'vegetarian':
            break;

            case 'vegan':
            break;

            case 'pesco-vegetarian':
            break;

            case 'pollotarian':
            break;

            case 'restriction':
            break;

            case 'low_calories':
            break;
        }

        //si l'utilisteur n'a pas choisi un régime à contrôle de calories (si les calories sont null) : on met les limites de calories à 2000 ou 2500 en fonction du sexe
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