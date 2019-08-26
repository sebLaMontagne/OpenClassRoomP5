<?php

session_start();

spl_autoload_register(function($class)
{
    $classesFolders = ['Models', 'Controllers', 'Managers', 'ViewModels'];
    foreach($classesFolders as $classFolder)
    {
        $file = $classFolder.'/'.$class.'.php';

        if(file_exists($file))
        {
            require_once($file);
            break;
        }
    }
});