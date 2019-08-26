<?php

class AppreciationController extends Controller
{
    public function likeDish()
    {
        if(empty($_SESSION['id']) || empty($_GET['id']))
        {
            exit(header('javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackDishAppreciation;
        $viewModel->likeDish();
        exit(header('location: dish-'.$_GET['id'].'.'.$_GET['lang']));
    }

    public function dislikeDish()
    {
        if(empty($_SESSION['id']) || empty($_GET['id']))
        {
            exit(header('javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackDishAppreciation;
        $viewModel->dislikeDish();
        exit(header('location: dish-'.$_GET['id'].'.'.$_GET['lang']));
    }

    public function likeArticle()
    {
        if(empty($_SESSION['id']) || empty($_GET['id']))
        {
            exit(header('javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackArticleAppreciation;
        $viewModel->likeArticle();
        exit(header('location: article-'.$_GET['id'].'.'.$_GET['lang']));
    }

    public function dislikeArticle()
    {
        if(empty($_SESSION['id']) || empty($_GET['id']))
        {
            exit(header('javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackArticleAppreciation;
        $viewModel->dislikeArticle();
        exit(header('location: article-'.$_GET['id'].'.'.$_GET['lang']));
    }
}