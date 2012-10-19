<?php
/*

Suggested modal or popup dimensions: 915px x 650px with no scrollbars

*/

// Absolute server path location to the uploads folder
define("MEDIA_LOCATION", $_SERVER['DOCUMENT_ROOT']."/filemanager/uploads"); // No trailing slashes

// URL path to the uploads folder
define("MEDIA_LOCATION_URL", "uploads"); // No trailing slashes

// MAX file upload size in MB
define("MAX_UPLOAD_SIZE","10");

// Pagination limit. Files listed on each page.
define("PAGINATE", "ON"); // ON/OFF
define("PAGINATE_LIMIT", "18");

// file manager's thumbnail size. Prob best to leave alone unless you are changing the file manger UI.
define("THUMB_MAX_WIDTH", "80");
define("THUMB_MAX_HEIGHT", "100");

// Allowed image types. Image files to create thumbs out of.
// For files like PSD or AI, list in the allowed documents below
$img_types = array('jpg', 'jpeg', 'png', 'gif'); // Absolutely no BMP files
// Allowed documents
$doc_types = array('doc', 'pdf', 'txt', 'rtf', 'xls', 'pages', 'docx', 'xlsx', 'ppt', 'pptx', 'psd', 'ai', 'mp3', 'mp4', 'avi', 'numbers', 'keynote', 'mpg', 'mpeg', 'ogg', 'ogv', 'html', 'css', 'flv', 'wav', 'webma', 'oga', 'm4a', 'zip', 'rar', 'tar', 'php');


// Max width and max height image sizes
$img_sizes = array(
                
                array("width" => 150 , "height" => 200),
                array("width" => 300 , "height" => 350)
                
                );
?>