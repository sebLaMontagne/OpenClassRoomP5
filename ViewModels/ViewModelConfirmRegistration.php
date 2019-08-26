<?php

class ViewModelConfirmRegistration extends ViewModel
{
    private $userManager;
    private $user;

    public function confirmUser()
    {
        if(preg_match($this->_tokenRegex, $_GET['token']))
        {
            $this->user = $this->userManager->getUserByToken($_GET['token']);
    
            if($this->user != null)
            {
                if($this->user->isTriggered() == 0)
                {
                    $this->userManager->triggerUser($this->user);
                }
            }
        }
    }
    
    public function __construct()
    {
        include('constantes.php');
        $this->_tokenRegex = $TOKEN_REGEX;

        parent::__construct();
        $this->_title       =   $this->_strings['CONFIRM_ACCOUNT_TITLE'];
        $this->_view        =   'Views/confirmRegistration.html';
        $this->userManager  =   new UserManager;
    }
}