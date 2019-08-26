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
        if($_SESSION['id'] == '')
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
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelDishTrad;
        $viewModel->display();
    }
}