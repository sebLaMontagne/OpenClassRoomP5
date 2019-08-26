<?php

class ViewModelBackDishAppreciation extends ViewModel
{
    private $appreciationManager;

    public function likeDish()
    {
        if($this->appreciationManager->isDishAppreciationExists($_GET['id'], $_SESSION['id'], 1))
        {
            // On ne fait rien
        }
        elseif($this->appreciationManager->isDishAppreciationExists($_GET['id'], $_SESSION['id'], 0))
        {
            // On va chercher l'appreciation et la changer en dislike
            $appreciationId = $this->appreciationManager->getDishAppreciation($_SESSION['id'], $_GET['id'])->Id();
            $this->appreciationManager->updateDishAppreciation($appreciationId, 1);
        }
        else
        {
            // On créé une nouvelle entrée pour l'utilisateur, le plat et l'appreciation
            $this->appreciationManager->saveDishAppreciation($_GET['id'], $_SESSION['id'], 1);
        }
    }

    public function dislikeDish()
    {     
        if($this->appreciationManager->isDishAppreciationExists($_GET['id'], $_SESSION['id'], 0))
        {
            // On ne fait rien
        }
        elseif($this->appreciationManager->isDishAppreciationExists($_GET['id'], $_SESSION['id'], 1))
        {
            // On va chercher l'appreciation et la changer en dislike
            $appreciationId = $this->appreciationManager->getDishAppreciation($_SESSION['id'], $_GET['id'])->Id();
            $this->appreciationManager->updateDishAppreciation($appreciationId, 0);
        }
        else
        {
            // On créé une nouvelle entrée pour l'utilisateur, le plat et l'appreciation
            $this->appreciationManager->saveDishAppreciation($_GET['id'], $_SESSION['id'], 0);
        }
    }

    public function __construct()
    {
        $this->appreciationManager = new AppreciationManager;
    }
}