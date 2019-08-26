<?php

class DefaultController extends Controller
{
    public function notFound()
    {
        $viewModel = new ViewModelNotFound;
        $viewModel->display();
    }

    public function home()
    {
        $viewModel = new ViewModelHome;
        $viewModel->display();
    }

    public function search()
    {
        if(empty($_POST['search']))
        {
            exit(header("location:javascript://history.go(-1)"));
        }

        $viewModel = new ViewModelSearch;
        $viewModel->getResults();
        $viewModel->display();
    }

    public function backContent()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackContent;
        $viewModel->display();
    }

    public function traductor()
    {
        if(empty($_SESSION['username']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelTraductor;
        $viewModel->display();
    }
}