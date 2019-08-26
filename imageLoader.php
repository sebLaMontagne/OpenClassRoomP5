<?php
include('autoloader.php');

$userManager = new UserManager;
$authorizedExtensions = ['jpg','jpeg','gif','tiff','png'];
$fileExtension = strtolower(pathinfo($_FILES['newAvatar']['name'], PATHINFO_EXTENSION));

if( in_array($fileExtension, $authorizedExtensions) && 
    $_FILES['newAvatar']['size'] < 10000000)
{
    unlink('Ressources/img/avatars/'.$_SESSION['avatar']);
    $_SESSION['avatar'] = uniqid().'.'.$fileExtension;
    $userManager->updateAvatar($_SESSION['id'], $_SESSION['avatar']);
    move_uploaded_file($_FILES['newAvatar']['tmp_name'], 'Ressources/img/avatars/'.$_SESSION['avatar']);

    header('Cache-Control: must-revalidate');
    header('Location: home.'.$_GET['lang']);
}