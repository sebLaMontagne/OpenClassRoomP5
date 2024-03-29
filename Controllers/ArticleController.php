<?php

class ArticleController extends Controller
{
    public function articleEditor()
    {
        if(!$_SESSION['isAdmin'] && !$_SESSION['isWriter'])
        {
            exit(header("location:javascript://history.go(-1)"));
        }

        $viewModel = new ViewModelArticleEditor;
        
        if(!empty($_POST['category']) && !empty($_POST['title']) && !empty($_POST['article_content']) && !empty($_FILES))
        {
            $viewModel->saveArticle();
        }
        elseif(!empty($_POST['newCategoryName']))
        {
            $viewModel->saveCategory();
        }
        $viewModel->display();
    }

    public function articles()
    {
        $viewModel = new ViewModelArticles;
        $viewModel->getArticles();
        $viewModel->display();
    }

    public function article()
    {
        if(empty($_GET['id']))
        {
            exit(header('location:javascript://history.go(-1)'));
        }
        
        $viewModel = new ViewModelArticle;
        $viewModel->display();
    }

    public function backArticle()
    {
        if($_SESSION['isAdmin'] != 1 || empty($_GET['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackArticle;
        $viewModel->getArticle($_GET['id']);
        $viewModel->display();
    }

    public function confirmArticle()
    {
        if($_SESSION['isAdmin'] != 1 || empty($_GET['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackArticle;
        $viewModel->confirmArticle($_GET['id']);
        exit(header('location: backContent.'.$_GET['lang']));
    }

    public function refuseArticle()
    {
        if($_SESSION['isAdmin'] != 1 || empty($_GET['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackArticle;
        $viewModel->refuseArticle($_GET['id']);
        exit(header('location: backContent.'.$_GET['lang']));
    }

    public function articleTrad()
    {
        if(!$_SESSION['isAdmin'] && !$_SESSION['isTranslator'])
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelArticleTrad;

        if(!empty($_POST))
        {
            $viewModel->saveArticleTrad($_GET['transLang'], $_GET['id'], $_POST['articleTitle'], $_POST['articleContent']);
            exit(header('location: traductor.'.$_GET['lang']));
        }
        else
        {
            $viewModel->display();
        }
    }
}