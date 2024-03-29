<?php require_once('include.php'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>File Manager</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="css/style.css">
	<script src="js/modernizr.min.js"></script>
</head>
<body>

    <div id="sticky-filemenu">
        <div class="sticky-inner">
            <a href="#" id="upload-modal-button"><i class="icon-upload"></i> Add Files</a>
            <div id="create-folder-wrap">
                <button type="submit" id="new-folder-button" class="button small">New Folder</button>
            </div>
        </div>
    </div>
    
    
    <!-- CAN'T TOUCH THIS -->
        <div id="file-tree-wrap" class="hide-tree">
            <div id="file-tree" class="hide-tree"><ul class="jqueryFileTree start"><li class="wait">Loading...<li></ul></div>
            <a href="#" id="file-tree-tab"><i class="icon-folder-close"></i></a>
        </div>
        <div id="files-container"></div>
    <!-- // -->
    
    <?php include('modals.php'); ?>
    <?php 
    echo '<span id="pl-settings" style="display: none">{ "max_upload" : "'.MAX_UPLOAD_SIZE.'", "file_type" : "'.$_GET['file_type'].'", "img_types" : "'.implode(",",$img_types).'", "doc_types" : "'.implode(",",$doc_types).'" }</span>';
    ?>
    <script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plupload.js"></script>
    <script src="js/plupload.flash.js"></script>
    <script src="js/plupload.html4.js"></script>
    <script src="js/plupload.html5.js"></script>
    <script src="js/jquery.Jcrop.min.js"></script>
    <script src="js/jquery.blockUI.js"></script>
    <script>

        var crop_minWidth = parseInt(<?php echo $_GET['crop_minWidth']; ?>);
        var crop_minHeight = parseInt(<?php echo $_GET['crop_minHeight']; ?>);

        var crop_maxWidth = parseInt(<?php echo $_GET['crop_maxWidth']; ?>);
        var crop_maxHeight = parseInt(<?php echo $_GET['crop_maxHeight']; ?>);
       
     
    </script>
    <script src="js/script.js"></script>
    <script>
    $(document).ready(function() {
        $("body").on("click", '.file-option-item, #select-image-button', function(e){
            e.preventDefault();
            if($(this).hasClass('file-option-item')){
                var url = $(this).data("path");
            } else {
                var url = $("#edit-image").data("edit-image-url");
            }
            // CKEditor /////////////////////////////////////////
            <?php if($_GET['editor']=="ckeditor"){ ?>
            var funcNum = <?php echo $_GET['CKEditorFuncNum']; ?>
    		// Call CKEditor function to insert the URL
    		window.opener.CKEDITOR.tools.callFunction(funcNum, url);
    		// Close Windows
    		window.close();
    		<?php } ?>
    		// END CKEditor /////////////////////////////////////
    		
    		
    		// Centrifuge CMS Standalone ////////////////////////
    		<?php if($_GET['editor']=="standalone"){ ?>
    		var el = '<?php echo filter_var($_GET['el'], FILTER_SANITIZE_STRING); ?>';
    		window.opener.$.fn.rsFileInsert(el,url, <?php echo filter_var($_GET['show_image'], FILTER_SANITIZE_STRING) ?>);
    		window.close();
    		return;
    		<?php } ?>
    		/////////////////////////////////////////////////////
    		
        });
    }); 
    </script>
</body>	
</html>