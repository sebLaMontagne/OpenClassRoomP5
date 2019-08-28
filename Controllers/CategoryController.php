<?php
class CategoryController extends Controller
{
    public function categoryTrad()
    {
        if($_SESSION['isAdmin'] != 1 || $_SESSION['isTranslator'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelCategoryTrad;

        if(!empty($_POST))
        {
            $viewModel->saveCategoryTrad($_GET['transLang'], $_GET['id'], $_POST['categoryName']);
            exit(header('location: traductor.'.$_GET['lang']));
        }

        $viewModel->display();
    }

    public function confirmCategory()
    {
        if($_SESSION['isAdmin'] != 1 || empty($_GET['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackCategory;
        $viewModel->confirmCategory($_GET['id']);
        exit(header('location: backContent.'.$_GET['lang']));
    }

    public function refuseCategory()
    {
        if($_SESSION['isAdmin'] != 1 || empty($_GET['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackCategory;
        $viewModel->refuseCategory($_GET['id']);
        exit(header('location: backContent.'.$_GET['lang']));
    }
}