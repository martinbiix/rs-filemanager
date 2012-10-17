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
            <a href="#" id="upload-modal-button"><i class="icon-upload"></i> Add File(s)</a>
            <div id="create-folder-wrap">
                <button type="submit" id="new-folder-button" class="button primary small">New Folder</button>
            </div>
        </div>
    </div>
    
    <?php include('uploader.php'); ?>
    
    <div id="error"></div> 
    
    <!-- CAN'T TOUCH THIS -->
    <div id="files-container"></div>
    <!-- // -->
    
    <?php include('modals.php'); ?>

    <script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plupload.js"></script>
    <script src="js/plupload.silverlight.js"></script>
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
        
        var uploader = new plupload.Uploader({
    		runtimes : 'html5,flash,silverlight',
    		browse_button : 'pickfile',
    		multipart : true,
    		drop_element : 'filedrop',
    		max_file_size : '<?php echo MAX_UPLOAD_SIZE ?>mb',
    		unique_names : true,
    		url : 'index.php',
    		flash_swf_url : 'js/plupload.flash.swf',
    		silverlight_xap_url : 'js/plupload.silverlight.xap',
    		filters : [
    		  <?php if($_GET['file_type'] == "images"){ ?>
    			{title : "Image files", extensions : "<?php echo implode(",",$img_types); ?>"}
    			<?php } elseif ($_GET['file_type'] == "files"){ ?>
    			{title : "Documents", extensions : "<?php echo implode(",",$doc_types); ?>"}
    			<?php } else { ?>
    			{title : "Image files", extensions : "<?php echo implode(",",$img_types); ?>"},
    			{title : "Documents", extensions : "<?php echo implode(",",$doc_types); ?>"}
    			<?php } ?>
    		]
    	});
    </script>
    <script src="js/script.js"></script>
    <script>
    $(document).ready(function() {
        $("body").on("click", '.image-option-item, .file-option-item', function(e){
            e.preventDefault();
            var url = $(this).attr("data-url");
            
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
    		$( ".file-edit-modal" ).modal("hide");
    		
    		return;
    		<?php } ?>
    		$( ".file-edit-modal" ).modal("hide");
    		alert('This option will normally load the image or file into your content editor, such as CKEditor.');
    		/////////////////////////////////////////////////////
    		
        });
    }); 
    </script>
</body>	
</html>