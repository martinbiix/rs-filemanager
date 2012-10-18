<?php
session_start();
require_once('config.php');
require_once('FileManager.php');
require_once('FileManagerView.php');

if(isset($_GET['file_type']) && ( strtolower($_GET['file_type']) == "files" || strtolower($_GET['file_type']) == 'images') ){
    $fileType = htmlentities(strtolower($_GET['file_type']));
    $_SESSION['file_type'] = $fileType;
} 

if(strtolower($_GET['list_view']) == 'list'){
    $_SESSION['list'] = true;
}
if(strtolower($_GET['list_view']) == 'folder'){
    $_SESSION['list'] = false;
}

if(isset($_GET['order_by'])){
    $_SESSION['order_by'] = strtolower(htmlentities($_GET['order_by']));
    $_SESSION['order_type'] = strtolower(htmlentities($_GET['order_type']));
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
if(!isset($_GET['list_view'])){
    $_SESSION['list'] = false;
}
if(!isset($_GET['order_by'])){
    $_SESSION['order_by'] = false;
    $_SESSION['order_type'] = false;
}
?>