<?php
class ViewModelBackUsers extends ViewModel
{
    public $users;

    private $userManager;
    private $messageManager;

    public function promoteCooker($id)
    {
        $this->userManager->promoteCooker($id);
    }

    public function demoteCooker($id)
    {
        $this->userManager->demoteCooker($id);
    }

    public function promoteWriter($id)
    {
        $this->userManager->promoteWriter($id);
    }

    public function demoteWriter($id)
    {
        $this->userManager->demoteWriter($id);
    }

    public function promoteTranslator($id)
    {
        $this->userManager->promoteTranslator($id);
    }

    public function demoteTranslator($id)
    {
        $this->userManager->demoteTranslator($id);
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