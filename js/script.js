var jcrop_api;
var crop_x;
var crop_y;
var crop_x2;
var crop_y2;
var crop_width;
var crop_height;


function loader(){
    $.blockUI({ css: { backgroundColor: 'none', border: 'none', color: '#fff' }, message: 'Loading...', timeout: 1000, fadeIn:  100, fadeOut:  100 });
}

function show_error(error){
    alert(error);   
}

function load_files(path){

    loader();
    $.post("index.php?action=LOAD_FILES", { path: path }, function(){
    }).success(function(data){
        $("#files-container").html(data);
    }).error(function(xhr, ajaxOptions, thrownError){
        show_error(thrownError);
    }).complete(function() {
        $.unblockUI();
    });

}

function change_list_view(path, type){

    loader();
    $.post("index.php?action=LOAD_FILES&list_view="+type, { path: path }, function(){
    }).success(function(data){
        $("#files-container").html(data);
    }).error(function(xhr, ajaxOptions, thrownError){
        show_error(thrownError);
    }).complete(function() {
        $.unblockUI();
    });

}

function change_order_view(path, order_by, order_type){

    loader();
    $.post("index.php?action=LOAD_FILES&order_by="+order_by+'&order_type='+order_type, { path: path }, function(){
    }).success(function(data){
        $("#files-container").html(data);
    }).error(function(xhr, ajaxOptions, thrownError){
        show_error(thrownError);
    }).complete(function() {
        $.unblockUI();
    });

}

function get_path(){
    var path = '/' + $("#current-location").val();
    return path;
}

function reload_edit_image(path){

    $( "#file-to-edit" ).load('index.php?action=EDIT_IMAGE', { path: path } );
    
}

function showCoords(c){
                    
    crop_x = c.x;
    crop_y = c.y;
    crop_x2 = c.x2;
    crop_y2 = c.y2;
                    
    crop_width = c.w;
    crop_height = c.h;
                    
    $("#w").html(crop_width);
    $("#h").html(crop_height);

}

function image_crop(){
                            
    jcrop_api=null;
                
    jcrop_api = $.Jcrop("#edit-image");
                 
    jcrop_api.setOptions({ onChange: showCoords,
        onSelect: showCoords,
        setSelect:   [ 100, 100, 20, 20 ],
        minSize: [ crop_minWidth, crop_minHeight ],
        maxSize: [ crop_maxWidth, crop_maxHeight ]
    });
                
    jcrop_api.enable();
                
                
    $("#save-crop-image-button").click(function(e){
        e.preventDefault();
                    
        var crop_path = $("#crop-path").val();
        $( "#image-options" ).html("Reloading images...");
                    
        $.post("index.php?action=CROP_IMAGE", { w: crop_width, h: crop_height, x: crop_x, y: crop_y, x2: crop_x2, y2: crop_y2, path: crop_path }, function(){ 
        }).success(function(data){
                    
            var obj = jQuery.parseJSON(data);
                        
            $("#file-to-edit").html('<p><img src="'+obj.cropped_image+'"></p><input type="hidden" id="crop-path"  value="'+obj.path+'">');
            $("#edit-image-message").html("Cropped Saved");
                        
                                                                 
        })
        .error(function(){  
        })
        .complete(function() { 
            //var curr_location = $("#current-location").val();
            //path = "/" + curr_location;
            $( "#image-options" ).load("index.php?action=IMAGE_OPTIONS", { path: crop_path } );
        });
                 
    }); // END #save-crop-image-button
                
    
}

//$(document).ajaxStop($.unblockUI);
$(document).ready(function() {

    var path = '';

    $("#files-container").on("click", '.folder, .bread-path', function(e){

        e.preventDefault();

        path = $(this).attr("data-path");

        load_files(path);

    });
    
    $("body").on("click", '.rs-order-by', function(e){

        e.preventDefault();

        var order_by = $(this).attr("data-order");

        path = get_path();
        
        change_order_view(path, order_by, 'ASC');

    });
    
    
    $("body").on("click", '.paginate a', function(e){

        e.preventDefault();

        var page = $(this).attr("data-page");

        path = get_path();
        
        loader();
        $.post("index.php?action=LOAD_FILES&page="+page, { path: path }, function(){
        }).success(function(data){
            $("#files-container").html(data);
        }).error(function(xhr, ajaxOptions, thrownError){
            show_error(thrownError);
        }).complete(function() {
            $.unblockUI();
        });

    });


    $("#new-folder-button").click(function(e){

        e.preventDefault();

        $(".new-folder-modal").modal('show');
        $("#new-folder-name").focus();

    });
    
    
    $("body").on("click", '.list-view-button', function(e){
        e.preventDefault();
        
        path = get_path();
        
        if ( $(this).children().hasClass('icon-th-list') ) {
          $(this).children().removeClass('icon-th-list');
          $(this).children().addClass('icon-th');

          change_list_view(path, 'folder');
          
        } else if ( $(this).children().hasClass('icon-th') ) {
          $(this).children().removeClass('icon-th');
          $(this).children().addClass('icon-th-list');
          
          
          change_list_view(path, 'list');
          
        }    
    });
    
    
    $("#add-new-folder-button").click(function(e){

        e.preventDefault();

        var new_folder_name = $("#new-folder-name").val();
        path = get_path();

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
        var ok;

        if(file_type === 'dir'){
            ok = confirm("Are you sure you want to delete this folder?\nAll files and folders inside this folder will be deleted too.");
        } else {
            ok = confirm("Are you sure you want to delete this file? All additional thumbnails and sizes associated with this image will be deleted too.");
        }

        if(ok === true){

            $.post("index.php?action=DELETE_FOLDER", { path: path, file_type: file_type }, function(){
            }).success(function(){

                $(e.target).parent().parent().parent().fadeOut('slow');

            })
            .error(function(){

            })
            .complete(function() {

            });

        } // end if


    });
    
    // DELETE CUSTOM IMAGE SIZES AND CROPS
    $("body").on("click", '.delete-custom-image', function(e){
        e.preventDefault();
        
        path = $(this).attr("data-path");

        var ok = confirm("Are you sure you want to delete this image?");
        
        if(ok === true){
            
            $.post("index.php?action=DELETE_CUSTOM_IMAGE", { path: path }, function(){
            }).success(function(){

                $(e.target).parent().parent().parent().fadeOut('slow');

            })
            .error(function(){

            })
            .complete(function() {

            });
            
        }
        
    });




    // EDIT FILES
    $("#files-container").on("click", '.view-img-sibs, .edit-file', function(e){

        e.preventDefault();

        var path = $(this).attr('data-path');
        
        $( "#image-options" ).load('index.php?action=IMAGE_OPTIONS', { path: path } );
        $( "#file-to-edit" ).load('index.php?action=EDIT_IMAGE', { path: path });
        $( ".file-edit-modal" ).modal("show");
        
        
    });

    $('.file-edit-modal').on('hidden', function () {
        $("#file-to-edit").html('');
        $( "#image-options" ).html('');
        $("#edit-image-message").html('');
    });


    $('#cancel-crop').click(function (e) {
        e.preventDefault();
        $("#file-to-edit").html('');
        $( "#image-options" ).html('');
        $( ".file-edit-modal" ).modal("hide");
        $("#edit-image-message").html('');
    });

    $("body").on("click", '.edit-file-option', function(e){

        e.preventDefault();
        
        $( "#file-to-edit" ).html("Loading image...");
        
        var path = $(this).attr('data-path');

        $( "#file-to-edit" ).load('index.php?action=EDIT_IMAGE', { path: path });

    });
    
    
   $("body").on("click", '#crop-image-button', function(e){
        e.preventDefault();
        image_crop();
   }); 


   // RENAME FOLDERS
   $("#rename-folder-button").click(function(e){
       e.preventDefault();
       
       var folder_name = $("#folder-name").val();
       
       if(folder_name.length > 2){

           $.post("index.php?action=EDIT_FOLDER", { path: path, folder_name: folder_name }, function(){
           }).success(function(){

                $("#folder-name").val('');
                path = get_path();
                load_files(path);

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



    // ROTATE IMAGES
    $("#rotate-image").click(function(e){
        e.preventDefault();
        
        $("#edit-image-message").html('');
        
        var crop_path = $("#crop-path").val();
                
        $("#file-to-edit").html('<p>Rotating image...</p>');        
                
         $.post("index.php?action=ROTATE_IMAGE", { path: crop_path }, function(){ 
         }).success(function(){
            reload_edit_image(crop_path);                  
         }).error(function(){  
         }).complete(function() { 
         });
            
    });




   // FILE UPLOADS -----------------------------------
   //
   //
   //---------------------------------------------------


   $(".uploader-modal").modal('hide');

   $("#upload-modal-button").click(function(e){
       e.preventDefault();
       var cur_location = $("#current-location").val();
       if(cur_location.length === 0){
           cur_location = 'home';
       }
       $("#uploading-location").html('Uploading to: '+cur_location);
       $('.uploader-modal').modal('show');
   });
   $('.uploader-modal').on('hidden', function () {

       $("#uploadfiles").hide();
       $("#custom-sizes").hide();
       $('#filelist').html('<span class="no-files-selected">No file selected</span>');
       $("#upload-progress").css("width", '0');
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


    

    uploader.bind('Init', function(up, params) {
        $('#filelist').html('<span class="no-files-selected">No file selected</span>');
    });

    uploader.bind('FilesAdded', function(up, files) {

        $.each(files, function(i, file) {

                $(".no-files-selected").hide();

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
            
            $("#upload-progress").css("width", '0');
            $("#uploadfiles").show();
            
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
            
            $("#upload-progress").css("width", '0px');
            
            path = get_path();
            
            load_files(path);
            
            up.refresh();
            up.stop();

        } else {
        
            $('#'+file.id).remove();
    
            $("#upload-progress").css("width", '0px');
    
            $(".upload-error").hide();
            $(".upload-error .notify-inner").html('');
            $("#uploadfiles").hide();
            $("#custom-sizes").hide();
        
        }
        
        up.refresh();

    });

    // UPLOADS COMPLETE
    uploader.bind('UploadComplete', function(up, files) {

        path = get_path();

        $("#custom-width").val('');
        $("#custom-height").val('');

        load_files(path);

        $.each(uploader.files, function(i, file) {
            uploader.removeFile(file.id);
        });

        uploader.refresh();

        $('.no-files-selected').show().html('<strong>Upload complete</strong>');
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
        $("#upload-progress").css("width", '0');
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
    
    $('#file-tree').fileTree();
    
    $("#file-tree-tab").click(function(e){
        e.preventDefault();
        //$("#file-tree").toggle();
        
        if($("#file-tree").hasClass("hide-tree")){
            
            $("#files-container").css("width", "83%");
            $("#file-tree").css("width", "17%");
            $("#file-tree").removeClass("hide-tree");
            $("#file-tree").addClass("show-tree");
            
        } else {
            
            $("#files-container").css("width", "100%");
            $("#file-tree").css("width", "0%");
            $("#file-tree").removeClass("show-tree");
            $("#file-tree").addClass("hide-tree");
        }
        
    });
    
    // Init load
    //load_files(path);

});


if(jQuery) (function($){
	
	$.extend($.fn, {
		fileTree: function(o, h) {
			
			$(this).each( function() {
				
				function showTree(c, t) {
					$(c).addClass('wait');
					$(".jqueryFileTree.start").remove();
					$.post('index.php?action=FILE_TREE', { path: t }, function(data) {
					   load_files(t);
						$(c).find('.start').html('');
						$(c).removeClass('wait').append(data);
						$(c).find('ul:hidden').slideDown({ duration: 100 });
						bindTree(c);
					});
				}
				
				function bindTree(t) {
					$(t).find('li a').bind('click', function(e) {
						e.preventDefault();
							
							if( $(this).parent().hasClass('collapsed') ) {
								// Expand								
								$(this).parent().find('ul').remove(); // cleanup
								showTree( $(this).parent(), $(this).attr('rel') );
								$(this).parent().removeClass('collapsed').addClass('expanded');
								$(this).children("i").removeClass('icon-folder-closed').addClass('icon-folder-open');
							} else {
								// Collapse
								$(this).parent().find('ul').slideUp({ duration: 100 });
								//$(this).parent().find('ul').remove(); // ADDED Hack
								$(this).parent().removeClass('expanded').addClass('collapsed');
								$(this).children("i").removeClass('icon-folder-open').addClass('icon-folder-closed');
								//showTree( $(this).parent(), $(this).attr('rel') ); // ADDED Hack
							}

					});

				}
				// Loading message
				$(this).append('<ul class="jqueryFileTree start"><li class="wait">Loading...<li></ul>');
				// Get the initial file list
				showTree( $(this), '' );
			});
		}
	});
	
})(jQuery);


