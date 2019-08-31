<?php

class DishController extends Controller
{
    public function dish()
    {
        if(empty($_GET['id']))
        {
            exit(header("location:javascript://history.go(-1)"));
        }

        $viewModel = new ViewModelDish;
        $viewModel->display();
    }
    
    public function dishes()
    {
        $viewModel = new ViewModelDishes;
        $viewModel->getAllDishes();
        $viewModel->display();
    }

    public function dishEditor()
    {
        if(!$_SESSION['isAdmin'] && !$_SESSION['isCooker'])
        {
            exit(header("location:javascript://history.go(-1)"));
        }

        $viewModel = new ViewModelDishEditor;
        if(empty($_POST) && empty($_FILES))
        {
            $viewModel->retrieveIngredients();
        }
        else
        {
            $viewModel->saveDish();
        }
        $viewModel->display();
    }

    public function backDish()
    {
        if($_SESSION['isAdmin'] != 1 || empty($_GET['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackDish;
        $viewModel->getDish();
        $viewModel->display();
    }

    public function confirmDish()
    {
        if($_SESSION['isAdmin'] != 1 || empty($_GET['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackDish;
        $viewModel->confirmDish($_GET['id']);
        exit(header('location: backContent.'.$_GET['lang']));
    }

    public function refuseDish()
    {
        if($_SESSION['isAdmin'] != 1 || empty($_GET['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackDish;
        $viewModel->refuseDish($_GET['id']);
        exit(header('location: backContent.'.$_GET['lang']));
    }

    public function dishTrad()
    {
        if(!$_SESSION['isAdmin'] && !$_SESSION['isTranslator'])
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelDishTrad;

        if(!empty($_POST))
        {
            $viewModel->saveDishTrad($_GET['transLang'], $_GET['id'], $_POST['dishName'], $_POST);
            exit(header('location: traductor.'.$_GET['lang']));
        }

        $viewModel->display();
    }
}