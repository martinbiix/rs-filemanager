<?php
session_start();
require_once('config.php');
require_once('FileManager.php');
require_once('FileManagerView.php');

if(isset($_GET['file_type']) && ($_GET['file_type'] == "files" || $_GET['file_type'] == 'images') ){
    $fileType = htmlentities($_GET['file_type']);
    $_SESSION['file_type'] = $fileType;
} 


if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || ($_REQUEST['action'] == "UPLOAD_FILE")) {
    
    $FileManager = new FileManager();
    $FileManager->img_sizes = $img_sizes;
    $FileManager->img_types = $img_types;
    $FileManager->doc_types = $doc_types;
    
    $FileManagerView = new FileManagerView($FileManager);
    echo $FileManagerView->view();
	die();

}

if(!isset($_GET['file_type'])){
    $_SESSION['file_type'] = false;
}

?>