<?php

class UserController extends Controller
{
    public function register()
    {
        if(isset($_SESSION['username']) && $_SESSION['username'] != '')
        {
            exit(header("location:javascript://history.go(-1)"));
        }

        $viewModel = new ViewModelRegister;

        //Inscription
        if( isset($_POST['registerName']) &&
            strlen($_POST['registerName']) > 5 &&
            isset($_POST['registerPassword']) &&
            strlen($_POST['registerPassword']) > 7 && 
            preg_match('@[a-z]+@', $_POST['registerPassword']) &&
            preg_match('@[A-Z]+@', $_POST['registerPassword']) &&
            preg_match('@[0-9]+@', $_POST['registerPassword']) &&
            $_POST['registerPassword'] === $_POST['registerPasswordConfirmation'] &&
            isset($_POST['registerEmail']) &&
            filter_var($_POST['registerEmail'], FILTER_VALIDATE_EMAIL) &&
            isset($_FILES['registerAvatar']))
        {
            $viewModel->registerUser();   
        }
        //Connexion
        elseif( isset($_POST['connectName']) && 
                isset($_POST['connectPassword']))
        {
            $viewModel->connectUser();
            
        }
        //Base form
        elseif(empty($_POST))
        {
            $viewModel->setDefault();
        }
        //Error
        else
        {
            exit(header('Location: home'.$_GET['lang']));
        }

        $viewModel->display();
    }

    public function confirmRegistration()
    {
        $viewModel = new ViewModelConfirmRegistration;
        $viewModel->confirmUser();
        $viewModel->display();
    }

    public function logout()
    {
        if($_SESSION['username'] == '')
        {
            exit(header("location:javascript://history.go(-1)"));
        }
        else
        {
            session_destroy();
            exit(header('Location: home.'.$_GET['lang']));
        }
    }

    public function dashboard()
    {
        if($_SESSION['username'] == '' && $_SESSION['isAdmin'] != 1)
        {
            exit(header("location:javascript://history.go(-1)"));
        }

        $viewModel = new ViewModelDashboard;
        $viewModel->display();
    }

    public function backUsers()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackUsers;
        $viewModel->display();
    }

    public function promoteUser()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackUsers;
        $viewModel->promoteUser($_GET['id']);

        if($_GET['return'] == 'messages')
        {
            $viewModel->deleteMessage($_GET['message']);
            exit(header('location: backMessages.'.$_GET['lang']));
        }
        else
        {
            exit(header('location: backUsers.'.$_GET['lang']));
        }
    }

    public function demoteUser()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackUsers;
        $viewModel->demoteUser($_GET['id']);
        exit(header('location: backUsers.'.$_GET['lang']));
    }

    public function banUser()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackUsers;
        $viewModel->banUser($_GET['id']);

        if($_GET['return'] == 'message')
        {
            exit(header('location: backMessage-'.$_GET['message'].'.'.$_GET['lang']));
        }
        else
        {
            exit(header('location: backUsers.'.$_GET['lang']));
        }
    }

    public function debanUser()
    {
        if($_SESSION['isAdmin'] != 1)
        {
            exit(header('location: javascript://history.go(-1)'));
        }

        $viewModel = new ViewModelBackUsers;
        $viewModel->debanUser($_GET['id']);
        
        if($_GET['return'] == 'message')
        {
            exit(header('location: backMessage-'.$_GET['message'].'.'.$_GET['lang']));
        }
        else
        {
            exit(header('location: backUsers.'.$_GET['lang']));
        }
    }
}