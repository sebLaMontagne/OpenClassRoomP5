<?php

class ViewModelNotFound extends ViewModel
{
    public function __construct()
    {
        parent::__construct();
        $this->_title       =   $this->_strings['NOTFOUND_TITLE'];
        $this->_view        =   'Views/notFound.html';
    }
}