<?php

class IngredientController extends Controller
{
    public function IngredientEditor()
    {
        if($_SESSION['username'] == '')
        {
            exit(header("location:javascript://history.go(-1)"));
        }

        $viewModel = new ViewModelIngredientEditor;

        if(!empty($_POST) && !empty($_FILES))
        {
            $viewModel->saveIngredient();
        }

        $viewModel->display();
    }

    public function confirmIngredient()
    {
        if($_SESSION['username'] == '' || empty($_GET['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackIngredient;
        $viewModel->confirmIngredient($_GET['id']);
        exit(header('location: backContent.'.$_GET['lang']));
    }

    public function refuseIngredient()
    {
        if($_SESSION['username'] == '' || empty($_GET['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackIngredient;
        $viewModel->refuseIngredient($_GET['id']);
        exit(header('location: backContent.'.$_GET['lang']));
    }

    public function ingredientTrad()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelIngredientTrad;
        $viewModel->display();
    }
}