<?php
class CategoryController extends Controller
{
    public function categoryTrad()
    {
        if($_SESSION['isAdmin'] != 1)
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
}