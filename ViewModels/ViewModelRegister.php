<?php

class ViewModelRegister extends ViewModel
{
    private $userManager;
    public $user;
    private $_isUsernameFree;
    private $_isEmailFree;

    public function registerUser()
    {
        $this->_isUsernameFree  =   $this->_userManager->isUsernameFree($_POST['registerName']);
        $this->_isEmailFree     =   $this->_userManager->isEmailFree($_POST['registerEmail']);

        if($this->_isUsernameFree && $this->_isEmailFree)
        { 
            $this->saveUserAvatar();
            $this->userManager->saveUser($_POST['registerName'], $_POST['registerEmail'], $_POST['registerPassword'], $fileNewName, $_GET['lang']);

            $this->_title = $this->_strings['REGISTER_CONFIRM_REGISTRATION_TITLE'];
        }
        else
        {
            $this->_title = $this->_strings['REGISTER_IDENTIFICATION_TITLE'];
        }
    }

    public function connectUser()
    {
        $this->user = $this->userManager->getUserByLogins($_POST['connectName'], $_POST['connectPassword']);

        if(isset($this->user) && $this->user->isTriggered())
        {
            $_SESSION['username']       = $this->user->name();
            $_SESSION['id']             = $this->user->id();
            $_SESSION['avatar']         = $this->user->avatar();
            $_SESSION['isBanned']       = $this->user->isBanned();
            $_SESSION['isAdmin']        = $this->user->isAdmin();
            $_SESSION['isCooker']       = $this->user->isCooker();
            $_SESSION['isWriter']       = $this->user->isWriter();
            $_SESSION['isTranslator']   = $this->user->isTranslator();
        
            exit(header('Location: home.'.$_GET['lang']));
        }
        $this->_title = $this->_strings['REGISTER_CONNECT_TITLE'];
    }

    public function setDefault()
    {
        $this->_title = $this->_strings['REGISTER_IDENTIFICATION_TITLE'];
    }

    private function saveUserAvatar()
    {
        $uploadFileDirectory = 'Ressources/img/avatars';
        do
        {
            $fileNewName = uniqid().'.'.strtolower(pathinfo($_FILES['registerAvatar']['name'], PATHINFO_EXTENSION));
            move_uploaded_file($_FILES['registerAvatar']['tmp_name'], $uploadFileDirectory.'/'.$fileNewName);

        }while(file_exists($uploadFileDirectory.$fileNewName));
    }

    public function __construct()
    {
        parent::__construct();
        $this->_view        =   'Views/register.html';
        $this->userManager  =   new UserManager;
    }
}