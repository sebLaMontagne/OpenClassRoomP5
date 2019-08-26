<?php
class ViewModelBackMessages extends ViewModel
{
    private $messageManager;
    public $messages;

    public function __construct()
    {
        parent::__construct();

        $this->_title = $this->_strings['BACKMESSAGES_TITLE'];
        $this->_view = 'Views/backMessages.html';

        $this->messageManager = new MessageManager;

        $this->messages = $this->messageManager->getAllMessages();
    }
}