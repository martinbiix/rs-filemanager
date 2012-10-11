<?php
require_once('config.php');
require_once('FileManager.php');
require_once('FileManagerView.php');
$FileManager = new FileManager();
$FileManager->img_sizes = $img_sizes;
$FileManager->img_types = $img_types;
$FileManager->doc_types = $doc_types;


if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || ($_REQUEST['action'] == "UPLOAD_FILE")) {
    
    $FileManagerView = new FileManagerView($FileManager);
    echo $FileManagerView->view();
	die();

}

?>