<?php
class ViewModelBackUsers extends ViewModel
{
    public $users;

    private $userManager;
    private $messageManager;

    public function promoteUser($id)
    {
        $this->userManager->promoteUser($id);
    }

    public function demoteUser($id)
    {
        $this->userManager->demoteUser($id);
    }

    public function banUser($id)
    {
        $this->userManager->banUser($id);
    }

    public function debanUser($id)
    {
        $this->userManager->debanUser($id);
    }

    public function deleteMessage($id)
    {
        $this->messageManager->deleteMessage($id);
    }

    public function __construct()
    {
        parent::__construct();

        $this->_title = $this->_strings['BACKUSERS_TITLE'];
        $this->_view = 'Views/backUsers.html';

        $this->userManager = new UserManager;
        $this->messageManager = new MessageManager;

        $this->users = $this->userManager->getAllUsers();
    }
}