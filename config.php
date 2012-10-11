<?php
// absolute server path Location of the file manager files
define("MEDIA_LOCATION", $_SERVER['DOCUMENT_ROOT']."/filemanager/media"); // No trailing slashes

// URL path to the media folder
define("MEDIA_LOCATION_URL", "media"); // No trailing slashes

// file manager's thumbnail size. Prob bset to leave alone.
define("THUMB_MAX_WIDTH", "80");
define("THUMB_MAX_HEIGHT", "100");

// Allowed image types. Image files to create thumbs out of.
// For files like PSD or AI, list in the allowed documents below
$img_types = array('jpg', 'jpeg', 'png', 'gif'); // Absolutely no BMP files
// Allowed documents
$doc_types = array('doc', 'pdf', 'txt', 'xls', 'pages', 'docx', 'xlsx', 'ppt', 'psd', 'ai');


// Max width and max height image sizes
$img_sizes = array(
                
                array("width" => 150 , "height" => 200),
                array("width" => 300 , "height" => 350)
                
                );
?>