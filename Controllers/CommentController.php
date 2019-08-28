<?php
class CommentController extends Controller
{
    public function saveArticleComment()
    {
        if(empty($_SESSION['id']))
        {
            exit(header('location: home.'.$_GET['lang']));
        }

        $viewModel = new ViewModelComment;
        $viewModel->saveArticleComment($_POST['id'], $_SESSION['id'], $_POST['content']);
        exit(header('location: article-'.$_POST['id'].'.'.$_GET['lang']));
    }

    public function saveDishComment()
    {
        if(empty($_SESSION['id']))
        {
            exit(header('location: home.'.$_GET['lang']));
        }

        $viewModel = new ViewModelComment;
        $viewModel->saveDishComment($_POST['id'], $_SESSION['id'], $_POST['content']);
        exit(header('location: dish-'.$_POST['id'].'.'.$_GET['lang']));
    }

    public function backComments()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: home.'.$_GET['lang']));
        }
        
        $viewModel = new ViewModelComment;
        $viewModel->getAllContents();
        $viewModel->display();
       
    }

    public function confirmArticleComment()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: home.'.$_GET['lang']));
        }

        $viewModel = new ViewModelComment;
        $viewModel->confirmArticleComment($_GET['id']);
        exit(header('location: backComments.'.$_GET['lang']));
    }

    public function confirmDishComment()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: home.'.$_GET['lang']));
        }

        $viewModel = new ViewModelComment;
        $viewModel->confirmDishComment($_GET['id']);
        exit(header('location: backComments.'.$_GET['lang']));
    }

    public function refuseArticleComment()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: home.'.$_GET['lang']));
        }

        $viewModel = new ViewModelComment;
        $viewModel->removeArticleComment($_GET['id']);
        exit(header('location: backComments.'.$_GET['lang']));

    }

    public function refuseDishComment()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: home.'.$_GET['lang']));
        }

        $viewModel = new ViewModelComment;
        $viewModel->removeDishComment($_GET['id']);
        exit(header('location: backComments.'.$_GET['lang']));
    }
}