<?php

class ViewModelHome extends ViewModel
{
    public function __construct()
    {
        parent::__construct();
        $this->_title       =   $this->_strings['HOME_TITLE'];
        $this->_view        =   'Views/home.html';
    }
}