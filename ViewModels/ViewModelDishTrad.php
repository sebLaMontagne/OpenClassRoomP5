<?php
class ViewModelDishTrad extends ViewModel
{
    private $dishManager;
    public $referenceDish;

    public function saveDishTrad($lang, $dish_id, $name, $post)
    {
        foreach($post as $key => $value)
        {
            if(preg_match('@dishStep[0-9]+@', $key))
            {
                $steps[] = $value;
            }
        }
        
        $this->dishManager->saveDishTrad($lang, $dish_id, $name, $steps);
    }

    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['DISH_TRAD_TITLE'];
        $this->_view = 'Views/dishTrad.html';

        $this->dishManager = new DishManager;

        $this->referenceDish = $this->dishManager->getDishVersion($_GET['id'], $_GET['baseLang']);
    }
}