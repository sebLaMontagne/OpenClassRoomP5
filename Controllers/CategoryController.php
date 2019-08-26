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
        $viewModel->display();
    }
}