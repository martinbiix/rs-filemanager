(function($){  
        
      var settings = {};
      
      $.fn.rsFileManager = function( options, method ) {
        
        var opts = $.extend({}, $.fn.rsFileManager.defaults, options);
        
        
        return this.each(function () {
          var ops = new RsfmClass($(this), opts);
        });
             
      
      };
      
      var RsfmClass = function (elm, opts) {
       
       var settings = opts; 
           
        elm.click(function(e){
            e.preventDefault();
            showFileManager(); 
        });

        
        var showFileManager = function() {
          
          var url = settings.location + '?el='+ settings.elementID + '&editor=standalone&file_type=' + settings.fileType + '&list_view=' + settings.listView + '&order_by=' + settings.orderBy + '&crop_minWidth=' + settings.cropMinWidth + '&crop_minHeight=' + settings.cropMinHeight + '&crop_maxWidth=' + settings.cropMaxWidth + '&crop_maxHeight=' + settings.cropMaxHeight + '&show_image=' + settings.showImage;    
          
          var top = (screen.height-(settings.height + 110))/2;
          var left = (screen.width-settings.width)/2;
            
          var parameters = "location=0,menubar=0,height="+settings.height+",width="+settings.width+",toolbar=0,scrollbars=0,status=0,resizable=1,left=" + left  + ",screenX=" + left + ",top=" + top  + ",screenY=" + top;
                        
          window.open(url, 'FileManager', parameters);
          
        };
      };
      
      $.fn.rsFileInsert = function( el, file, showImage ) {
            
            if($("#"+el).is("input")){
                $('#'+el).val(file); 
            } else if($("#"+el).is("a")){
                $('#'+el).prop("href",el); 
            } else {
                $('#'+el).html(file);
            }
            
            if(showImage){
                $('#'+el).after('<img src="'+file+'" class="rs-image">');
            }
      };
      
      
      $.fn.rsFileManager.defaults = {
        'location'      : 'filemanager/index.php', // URL location relative to this file
        'width'         : 940,                     // Width of the popup or modal
        'height'        : 675,                     // Height of the popup or modal
        'elementID'     : 'thumb1',                // element to insert the image,file url in
        'fileType'      : 'images',                // images | files | both
        'listView'      : 'folder',                // folder | list
        'orderBy'       : 'type',                  // type | name | filesize
        'showImage'     : true,                    // Shows image after elementID
        'cropMinWidth'  : 0,                       // Crop constraints
        'cropMinHeight' : 0,
        'cropMaxWidth'  : 0,
        'cropMaxHeight' : 0
    };
    
})(jQuery);