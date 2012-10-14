$(document).ajaxStop($.unblockUI);
$(document).ready(function() {

    var path = '';

    $("#files-container").on("click", '.folder, .bread-path', function(e){

        e.preventDefault();

        path = $(this).attr("data-path");

        load_files(path);

    });


    $("#new-folder-button").click(function(e){

        e.preventDefault();

        $(".new-folder-modal").modal('show');
        $("#new-folder-name").focus();

    });
    
    
    $("#add-new-folder-button").click(function(e){

        e.preventDefault();

        var new_folder_name = $("#new-folder-name").val();
        var curr_location = $("#current-location").val();
        path = '/' + curr_location;

        if(new_folder_name.length < 1){ return false; }


        $.post("index.php?action=CREATE_FOLDER", { path: path, new_folder: new_folder_name }, function(){


        }).success(function(){

            $("#new-folder-name").val("");
            $(".new-folder-modal").modal('hide');
            load_files(path);

        })
        .error(function(){
            alert("Folder already exists in this location. Please try another name.");
        })
        .complete(function() {

        });

    });
    
    $("#cancel-new-folder").click(function(e){
        e.preventDefault();
        $("#new-folder-name").val("");
        $(".new-folder-modal").modal('hide');
    });
    
    $('.new-folder-modal').on('hidden', function () {
        $("#new-folder-name").val("");
    });


    // DELETE FOLDERS/FILES
    $("#files-container").on("click", '.delete-folder, .delete-file', function(e){
        e.preventDefault();

        path = $(this).attr("data-path");
        var file_type = $(this).attr("data-type");


        if(file_type == 'dir'){
            var ok = confirm("Are you sure you want to delete this folder?\nAll files and folders inside this folder will be deleted too.");
        } else {
            var ok = confirm("Are you sure you want to delete this file? All additional thumbnails and sizes associated with this image will be deleted too.");
        }

        if(ok === true){

            $.post("index.php?action=DELETE_FOLDER", { path: path, file_type: file_type }, function(data){
            }).success(function(){

                $(e.target).parent().parent().parent().fadeOut('slow');

            })
            .error(function(){

            })
            .complete(function() {

            });

        } // end if


    });


    // IMAGE OPTIONS
    $("#files-container").on("click", '.view-img-sibs', function(e){
        e.preventDefault();

        path = $(this).attr("data-path");

        // Load modal with all the image options to choose from
        $( "#image-options" ).load('index.php?action=IMAGE_OPTIONS', { path: path } );
        $( ".image-options-modal" ).modal("show");

    });
    $('#cancel-image-options').click(function (e) {
        e.preventDefault();
        $("#image-options").html('');
        $( ".image-options-modal" ).modal("hide");
    });



    // EDIT FILES
    $("#files-container").on("click", '.edit-file', function(e){

        e.preventDefault();

        var path = $(this).attr('data-path');

        $( "#file-to-edit" ).load('index.php?action=EDIT_IMAGE', { path: path } );
        $( ".file-edit-modal" ).modal("show");


    });

    $('.file-edit-modal').on('hidden', function () {
        $("#file-to-edit").html('');
    });


    $('#cancel-crop').click(function (e) {
        e.preventDefault();
        $("#file-to-edit").html('');
        $( ".file-edit-modal" ).modal("hide");
    });



   // RENAME FOLDERS
   $("#rename-folder-button").click(function(e){
       e.preventDefault();
       
       var folder_name = $("#folder-name").val();
       
       if(folder_name.length > 2){

           $.post("index.php?action=EDIT_FOLDER", { path: path, folder_name: folder_name }, function(){
           }).success(function(){

                $("#folder-name").val('');
                var curr_location = $("#current-location").val();
                curr_path = '/' + curr_location;
                load_files(curr_path);

                $( ".folder-name-modal" ).modal("hide");

            })
            .error(function(xhr, status, error){
                alert('Folder already exists in this location. Please try another name.');
            })
            .complete(function() {});

        }
        
   });

    $("#files-container").on("click", '.edit-dir', function(e){

        e.preventDefault();

        path = $(this).attr('data-path');
        $( ".folder-name-modal" ).modal("show");

    });
    $("#cancel-rename-folder").click(function(e){
        e.preventDefault();
        $("#folder-name").val("");
        $(".folder-name-modal").modal('hide');
    });
    
    $('.folder-name-modal').on('hidden', function () {
        $("#folder-name").val("");
    });



    // Init load
    load_files(path);



   // FILE UPLOADS -----------------------------------
   //
   //
   //---------------------------------------------------


   $(".uploader-modal").modal('hide');

   $("#upload-modal-button").click(function(e){
       e.preventDefault();
       var cur_location = $("#current-location").val();
       if(cur_location.length == 0){
           cur_location = 'home';
       }
       $("#uploading-location").html('Uploading to: '+cur_location);
       $('.uploader-modal').modal('show');
   });
   $('.uploader-modal').on('hidden', function () {

       $("#uploadfiles").hide();
       $("#custom-sizes").hide();
       $('#filelist').html('<span class="no-files-selected">No file selected</span>');

       $(".upload-error").hide();
       $(".upload-error .notify-inner").html('');

       if(uploader){
		  $.each(uploader.files, function(i, file) {
            uploader.removeFile(file.id);
            });
		  uploader.refresh();
		  uploader.splice();
       }
   });


    var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight',
		browse_button : 'pickfile',
		multipart : true,
		drop_element : 'filedrop',
		max_file_size : '10mb',
		unique_names : true,
		url : 'index.php',
		flash_swf_url : 'js/plupload.flash.swf',
		silverlight_xap_url : 'js/plupload.silverlight.xap',
		filters : [
			{title : "Image files", extensions : "jpg,jpeg,png,gif"},
			{title : "Documents", extensions : "doc,docx,xls,xlsx,ppt,pages,numbers,rtf,txt,pdf,zip,tar,exe,dmg"}
		]
	});

	uploader.bind('Init', function(up, params) {
		$('#filelist').html('<span class="no-files-selected">No file selected</span>');
	});

	uploader.bind('FilesAdded', function(up, files) {

		$.each(files, function(i, file) {

	            $(".no-files-selected").remove();

	            $('#filelist').append(
	                '<div id="' + file.id + '">' +
	                file.name + ' (' + plupload.formatSize(file.size) + ') <a href="#" class="upload-delete"><i class="icon-remove"></i></a>' +
	            '</div>');

            	$("#"+file.id+" .upload-delete").click(function(e){
                	$("#"+file.id).remove();
                	uploader.removeFile(file);
                	e.preventDefault();
                });

	        });

			$("#uploadfiles").show();
			$("#custom-sizes").show();

	});

	uploader.bind('Error', function(up, err) {

	        $(".upload-error").show();

			$('.upload-error .notify-inner').append("<p>Error: " + err.code +
	            ", Message: " + err.message +
	            (err.file ? ", File: " + err.file.name : "") +
	            "</p>"
	        );

	        up.refresh();
	});

	uploader.bind('UploadProgress', function(up, file) {
		$("#upload-progress").css("width", file.percent+'%');
	});

	uploader.bind('BeforeUpload', function(up, file) {
	   // Custom size
	   var custom_width = $("#custom-width").val();
	   var custom_height = $("#custom-height").val();

    	uploader.settings.multipart_params = {path: $("#current-location").val(), action: "UPLOAD_FILE", custom_width: custom_width, custom_height: custom_height};
    });

	uploader.bind('FileUploaded', function(up, file, info) {

		var obj = $.parseJSON(info.response);

		if(obj.error){
    		
    		$(".upload-error").show();
			$(".upload-error .notify-inner").html("<p><span class='bold'>Error:</span> "  + obj.error.message + "</p>");

	        up.refresh();

			return false;
		}


		$("#upload-progress").css("width", '5px');

		$(".upload-error").hide();
		$(".upload-error .notify-inner").html('');
		$("#uploadfiles").hide();
		$("#custom-sizes").hide();

		uploader.refresh();

    });

    // UPLOADS COMPLETE
    uploader.bind('UploadComplete', function(up, files) {

		var curr_location = $("#current-location").val();
        var path = '/' + curr_location;

        $("#custom-width").val('');
	    $("#custom-height").val('');

		load_files(path);

		$.each(uploader.files, function(i, file) {
            uploader.removeFile(file.id);
       	});

		uploader.refresh();

		$('#filelist').html('<strong>Upload complete.</strong>');
		$("#upload-progress").css("width","0px");
		$(".upload-error").hide();
	    $(".upload-error .notify-inner").html('');
		$("#uploadfiles").hide();
		$("#custom-sizes").hide();

	});

	$('#uploadfiles').click(function(e) {

		uploader.start();
		e.preventDefault();

	});

	$("body").on("click", '#cancelfile', function(e) {

		$("#uploadfiles").hide();
		$(".upload-error").hide();
	    $(".upload-error .notify-inner").html('');
	    $("#custom-sizes").hide();

	    $(".uploader-modal").modal('hide');

		$.each(uploader.files, function(i, file) {
            uploader.removeFile(file.id);
       	});

		$('#filelist').html('<span class="no-files-selected">No file selected</span>');

		uploader.refresh();
		uploader.splice();

		e.preventDefault();
	});

	uploader.init();


	$("#filedrop").hover(
      function () {
        $(this).addClass("hover");
      },
      function () {
        $(this).removeClass("hover");
      }
    );
    
    $("#filedrop").bind('dragover',function(event){
        $(event.target).addClass("hover");
        event.stopPropagation();
        event.preventDefault();  
    });
    $("#filedrop").bind('dragleave',function(event){
        $(event.target).removeClass("hover");
        event.stopPropagation();
        event.preventDefault();  
    });
    $("#filedrop").bind('drop',function(event){
        $(event.target).removeClass("hover");
        event.stopPropagation();
        event.preventDefault();  
    });
    
    if(!Modernizr.draganddrop){
        $("#filedrop").hide();
        $(".upload-or").hide();
    }

});


function load_files(path){

    $.blockUI({ css: { backgroundColor: 'none', border: 'none', color: '#fff' }, message: 'Loading...', timeout: 1000, fadeIn:  100, fadeOut:  100 });

    $.post("index.php?action=LOAD_FILES", { path: path }, function(data){

        $("#files-container").html(data);

    }).success(function(){
    })
    .error(function(){

    })
    .complete(function() {

    });

}