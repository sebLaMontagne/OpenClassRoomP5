<?php

class ViewModelBackArticleAppreciation extends ViewModel
{
    private $appreciationManager;

    public function likeArticle()
    {
        if($this->appreciationManager->isArticleAppreciationExists($_GET['id'], $_SESSION['id'], 1))
        {
            // On ne fait rien
        }
        elseif($this->appreciationManager->isArticleAppreciationExists($_GET['id'], $_SESSION['id'], 0))
        {
            // On va chercher l'appreciation et la changer en dislike
            $appreciationId = $this->appreciationManager->getArticleAppreciation($_SESSION['id'], $_GET['id'])->Id();
            $this->appreciationManager->updateArticleAppreciation($appreciationId, 1);
        }
        else
        {
            // On créé une nouvelle entrée pour l'utilisateur, le plat et l'appreciation
            $this->appreciationManager->saveArticleAppreciation($_GET['id'], $_SESSION['id'], 1);
        }
    }

    public function dislikeArticle()
    {     
        if($this->appreciationManager->isArticleAppreciationExists($_GET['id'], $_SESSION['id'], 0))
        {
            // On ne fait rien
        }
        elseif($this->appreciationManager->isArticleAppreciationExists($_GET['id'], $_SESSION['id'], 1))
        {
            // On va chercher l'appreciation et la changer en dislike
            $appreciationId = $this->appreciationManager->getArticleAppreciation($_SESSION['id'], $_GET['id'])->Id();
            $this->appreciationManager->updateArticleAppreciation($appreciationId, 0);
        }
        else
        {
            // On créé une nouvelle entrée pour l'utilisateur, le plat et l'appreciation
            $this->appreciationManager->saveArticleAppreciation($_GET['id'], $_SESSION['id'], 0);
        }
    }

    public function __construct()
    {
        $this->appreciationManager = new AppreciationManager;
    }
}