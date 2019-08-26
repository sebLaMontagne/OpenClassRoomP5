<?php
class ViewModelBackMessage extends ViewModel
{
    private $messageManager;
    public $message;

    public function deleteMessage($id)
    {
        $this->messageManager->deleteMessage($id);
    }

    public function __construct()
    {
        parent::__construct();
        $this->_title = $this->_strings['BACKMESSAGE_TITLE'];
        $this->_view = 'Views/backMessage.html';

        $this->messageManager = new MessageManager;
        $this->message = $this->messageManager->getMessage($_GET['id']);
    }
}