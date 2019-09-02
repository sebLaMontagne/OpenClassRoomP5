<?php

class ViewModelIngredientEditor extends ViewModel
{
    public $_fileNewName;
    public $_ingredientIsSaved;
    private $ingredientManager;

    private function saveImage()
    {
        $uploadFileDirectory = 'Ressources/img/ingredients';
        $authorizedExtensions = ['jpg','jpeg','gif','tiff','png'];
        $fileExtension = strtolower(pathinfo($_FILES['ingredient_image']['name'], PATHINFO_EXTENSION));

        if(in_array($fileExtension, $authorizedExtensions) && $_FILES['ingredient_image']['size'] < 10000000)
        {
            do
            {
                $this->_fileNewName = uniqid().'.'.$fileExtension;
                move_uploaded_file($_FILES['ingredient_image']['tmp_name'], $uploadFileDirectory.'/'.$this->_fileNewName);

            }while(file_exists($uploadFileDirectory.$this->_fileNewName));
        }
    }

    public function saveIngredient()
    {
        $this->saveImage();

        $this->ingredientManager->saveIngredient(
            $this->_fileNewName, 
            $_POST['ingredient_calories'], 
            $_POST['ingredient_unit_weight'], 
            $_GET['lang'], 
            $_POST['ingredient_name'], 
            $_POST['ingredient_isPoultry'],
            $_POST['ingredient_isFish'],
            $_POST['ingredient_isSeaFood'],
            $_POST['ingredient_isFromAnimal'],
            $_POST['ingredient_isFruit'],
            $_POST['ingredient_isVegetable']
        );

        $this->_ingredientIsSaved = true;
    }
    
    public function __construct()
    {
        parent::__construct();
        $this->_view = 'Views/ingredientEditor.html';
        $this->_title = $this->_strings['INGREDIENT_EDITOR_TITLE'];

        $this->_ingredientIsSaved = false;
        $this->ingredientManager = new IngredientManager;
    }
}