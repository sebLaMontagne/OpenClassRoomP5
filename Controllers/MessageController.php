<?php

class MessageController extends Controller
{
    public function mailEditor()
    {
        if(empty($_SESSION['id']))
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelMessage;

        if(!empty($_POST['msg_type']) && !empty($_POST['msg_content']))
        {
            $viewModel->saveMessage();
        }

        $viewModel->display();
    }

    public function backMessages()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackMessages;
        $viewModel->display();
    }

    public function backMessage()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackMessage;
        $viewModel->display();
    }

    public function deleteMessage()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }
        
        $viewModel = new ViewModelBackMessage;
        $viewModel->deleteMessage($_GET['id']);
        exit(header('location: backMessages.'.$_GET['lang']));
    }
}