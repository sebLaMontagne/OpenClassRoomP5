<?php

class ViewModelDishEditor extends ViewModel
{
    private $ingredientManager;
    private $dishManager;

    public $_dishIsSaved;
    public $_ingredients;
    private $_fileNewName;
    private $_ingredientsList;
    private $_stepsList;
    private $_dishId;

    const AUTHORIZED_EXTENSIONS = ['png','jpg','jpeg','gif','tiff'];
    const UPLOAD_FILE_DIRECTORY = 'Ressources/img/dishes';

    public function retrieveIngredients()
    {
        $this->_ingredients = $this->ingredientManager->getAllIngredients();
    }

    public function saveDish()
    {
        //vérification et enregistrement de l'image
        $this->dishFileHandler($_FILES['dishImage']);

        //construction des tableaux d'ingrédients et d'étapes bien formaté à partir du $_POST
        $this->_ingredientsList = $this->buildIngredientsList($_POST);
        $this->_stepsList = $this->buildStepsList($_POST);

        //enregistrement du plat
        $this->dishManager->saveDish($_POST['dishName'], $_POST['dishType'], $_POST['shareNb'], $_POST['preparationTime'], $this->_fileNewName, $_SESSION['id'], $_SESSION['isAdmin'], $_GET['lang']);
        //récupération de l'ID du plat en db
        $this->_dishId = $this->dishManager->getDishByName($_POST['dishName'])->Id();
        //enregistrement des ingredients
        $this->ingredientManager->saveDishIngredients($this->_dishId, $this->_ingredientsList);
        //enregistrement des étapes
        $this->dishManager->saveDishSteps($this->_dishId, $this->_stepsList, $_GET['lang']);

        $this->_dishIsSaved = true;
    }

    private function dishFileHandler(array $fileInfos)
    {
        $fileExtension = strtolower(pathinfo($fileInfos['name'], PATHINFO_EXTENSION));
        
        if(in_array($fileExtension, self::AUTHORIZED_EXTENSIONS))
        {
            do
            {
                $this->_fileNewName = uniqid().'.'.$fileExtension;
                move_uploaded_file($fileInfos['tmp_name'], self::UPLOAD_FILE_DIRECTORY.'/'.$this->_fileNewName);
            }while(file_exists(self::UPLOAD_FILE_DIRECTORY.$this->_fileNewName));
        }
    }

    private function buildIngredientsList($post)
    {
        $ingredientsList = [];

        $ingredientId = null;
        $ingredientQuantity = null;
        $ingredientUnit = null;

        foreach($post as $key => $value)
        {
            if(preg_match('@^ingredient([0-9]+)$@', $key))
            {
                $ingredientId = $value;
            }
            if(preg_match('@^quantityIngredient([0-9]+)$@', $key))
            {
                $ingredientQuantity = $value;
            }
            if(preg_match('@^ingredientUnit([0-9]+)$@', $key))
            {
                $ingredientUnit = $value;
            }

            if($ingredientId != null && $ingredientQuantity != null && $ingredientUnit != null)
            {
                $ingredient = [
                    'id' => $ingredientId, 
                    'quantity' => $ingredientQuantity, 
                    'unit' => $ingredientUnit];

                $ingredientsList[] = $ingredient;

                $ingredientId = null;
                $ingredientQuantity = null;
                $ingredientUnit = null;
            }
        }

        return $ingredientsList;
    }

    private function buildStepsList($post)
    {
        $steps = [];
        $stepId = 0;

        foreach($post as $key => $value)
        {
            if(preg_match('@^step([0-9]+)$@', $key))
            {
                $stepId++;
                $steps[$stepId] = $value;
            }
        }

        return $steps;
    }

    public function __construct()
    {
        parent::__construct();
        $this->_title               =   $this->_strings['DISH_EDITOR_TITLE'];
        $this->_view                =   'Views/dishEditor.html';
        $this->ingredientManager    =   new IngredientManager;
        $this->dishManager          =   new DishManager;
        $this->_dishIsSaved         =   false;
    }
}