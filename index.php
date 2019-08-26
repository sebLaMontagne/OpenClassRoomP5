<?php

try
{
    require_once('autoloader.php');

    //Inclure un fichier de constantes à partir d'une classe Data utilitaire ne contenant que des consts
    include_once('constantes.php');
    
    //S'il manque l'action, la langue ou que la langue n'est pas supportée, 
    //on s'assure de donner une langue et une action valides dans l'URL

    if( empty($_GET['action']) || 
        empty($_GET['lang']) || 
        !in_array($_GET['lang'], $LANGUAGES))
    {
        exit(header('Location: home.fr'));
    }

    //On déclare les controllers de l'application ici. Leur traitement est traité après
    //dans un foreach

    $controllers[] = new DefaultController;
    $controllers[] = new UserController;
    $controllers[] = new DishController;
    $controllers[] = new ArticleController;
    $controllers[] = new IngredientController;
    $controllers[] = new AppreciationController;
    $controllers[] = new CommentController;
    $controllers[] = new MessageController;
    $controllers[] = new CategoryController;
    
    //On vérifie que l'action correspond bien à une méthode d'un controller existant
    //Si l'action correspond, alors on execute la méthode associée.
    //Autrement, on renvoie l'utilisateur sur la vue indiquant un fichier non trouvé

    $methodFound = false;
    foreach($controllers as $controller)
    {
        if(!$methodFound)
        {
            if(method_exists($controller, $_GET['action']))
            {
                $controller->{$_GET['action']}();
                $methodFound = true;
            }
        }
        else
        {
            break;
        }
    }
    if(!$methodFound)
    {
        exit(header('Location: notFound.'.$_GET['lang']));
    }
}
catch(Exception $e)
{
    echo 'Exception reçue : '.$e->getMessage();
}