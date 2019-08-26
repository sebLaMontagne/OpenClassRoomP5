<?php
class ViewModelMessage extends ViewModel
{
    private $messageManager;
    public $userMessages;
    public $msgIsSaved;

    public function saveMessage()
    {
        if($this->userMessages < 3)
        {
            $this->messageManager->saveMessage($_POST['msg_type'], $_POST['msg_content'], $_SESSION['id']);
            $this->msgIsSaved = true;
        }
        else
        {
            $this->msgIsSaved = false;
        }
        
    }
    
    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['MAILEDITOR_TITLE'];
        $this->_view = 'Views/mailEditor.html';

        $this->messageManager = new MessageManager;

        $this->userMessages = count($this->messageManager->getUserMessages($_SESSION['id']));
    }
}