<?php

abstract class ViewModel
{
    protected $_strings;
    protected $_title;
    protected $_view;
    protected $_template = 'Views/template.html';

    public function display()
    {
        ob_start();
        require($this->_view);
        $content = ob_get_clean();
        $title = $this->_title;
        include($this->_template);
    }

    public function __construct()
    {
        include('languages/'.$_GET['lang'].'.php');
        $this->_strings = $strings;
    }
}