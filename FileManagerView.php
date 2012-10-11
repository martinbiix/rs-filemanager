<?php
class FileManagerView{

public $results = '';

private $FileManager;
    
    function __construct($FileManager){
        
       $this->FileManager = $FileManager;
        
       $action = htmlentities($_REQUEST['action']);
        
        switch ($action) {
            case "LOAD_FILES":
                $this->load_files();
                break;
            case "EDIT_IMAGE":
                $this->edit_image();
                break;
            case "CROP_IMAGE":
                $this->crop_image();
                break;
            case "IMAGE_OPTIONS":
                $this->image_options();
                break;
            case "CREATE_FOLDER":
                $this->create_folder();
                break;
            case "DELETE_FOLDER":
                $this->delete_folder();
                break;
            case "UPLOAD_FILE":
                $this->upload_file();
                break;                        
        }
             
    }
    
    
    
    
    public function view(){
        return $this->results;
    }
    
    
    
    
    /**
     * load_files function.
     * 
     * @access private
     * @return html
     */
    private function load_files(){
        
        $files = $this->FileManager->dir_list();
        $bread_path = $this->FileManager->bread_path();
        $path = $this->FileManager->path();
        $current_folder = $this->FileManager->current_folder();
        $bread_crumb = $this->FileManager->bread_crumb();
        
        $html =  '<div id="bread-wrap">';
        
        if(strlen($path)){
            $html .=  '&laquo; <a class="bread-path" href="#" data-path="'.$bread_path.'">Back</a> | <a href="#" class="bread-path" data-path="">Home</a> / '.$bread_crumb;
        } else {
            $html .=  'Home';
        }
        
        $html .=  '</div>';
        
                
        $html .=  '<input type="hidden" name="current_location" id="current-location" value="'.$path.'">';
        
        if(count($files)>0){
        
                function array_sort($a, $subkey, $order=SORT_ASC) {
                	foreach($a as $k=>$v) {
                		$b[$k] = strtolower($v[$subkey]);
                	}
                
                	switch ($order) {
                            case SORT_ASC:
                                asort($b);
                            break;
                            case SORT_DESC:
                                arsort($b);
                            break;
                    }
                	
                	foreach($b as $key=>$val) {
                		$c[] = $a[$key];
                	}
                	return $c;
                }
                
                
                $files = array_sort($files, 'name');
            
                
                // List folders
                foreach($files as $file){
                        
                    if($file['file_type'] == 'dir'){            
            
                        $class_item = "dir";
                        $class_delete = "delete-folder";
                        $class_edit = "edit-dir"; 
            
                        
                        $html .=  '<div class="grid-item '.$class_item.'">
                                    <a class="folder" href="#" data-path="'.$file["base_path"].'"><i class="icon-folder-close folder-thumb"></i><br><span class="file-name">'.$file["name"].'</span></a>
                                    <div class="file-actions">
                                        <a class="'.$class_edit.'" href="#" data-path="'.$file["base_path"].'" data-type="'.$class_item.'"><i class="icon-pencil"></i></a>
                                        <a class="'.$class_delete.'" href="#" data-path="'.$file["base_path"].'" data-type="'.$class_item.'"><i class="icon-trash"></i></a>
                                    </div>
                            </div>';
                    }
                }
                
                // List Files
                foreach($files as $file){
                   
                   if($file['file_type'] == 'file'){
                        
                        $class_item = "file";
                        $class_delete = "delete-file";
                        $class_edit = "edit-file";
                        
                        $allowed_img = array("image/jpg"=>"jpg", "image/jpeg"=>"jpeg", "image/png"=>"png", "image/gif"=>"gif");
                        
                        $html .=  '<div class="grid-item '.$class_item.'">';
                        
                        //echo MEDIA_LOCATION.$file["base_path"];
                        
                        if(file_exists(MEDIA_LOCATION.$file["base_path_thumb"])){
                            $attr = getimagesize(MEDIA_LOCATION.$file["base_path_thumb"]);
                        }
                        $mime = $attr['mime'];
                        
                        
                        if(array_key_exists($mime,$allowed_img)) {
                        
                            $class_link = "view-img-sibs";
                            
                        
                            $html .=  '      <a class="'.$class_link.'" href="#" data-path="'.$file["base_path"].'">
                                            <img src="'.MEDIA_LOCATION_URL.$file["base_path_thumb"].'" '.$attr[3].' class="max-width">
                                            <br>
                                            <span class="file-name">'.$file["name"].'</span>
                                        </a>';
                        
                        } else {
    
                            
                            $html .=  '      <a class="file" href="#" data-path="'.$file["base_path"].'">
                                            <br>
                                            <span class="file-name">'.$file["name"].'</span>
                                        </a>';
                            
                        }
                        
                        $html .=  '      <div class="file-actions">
                                        <a class="'.$class_edit.'" href="#" data-path="'.$file["base_path"].'" data-type="'.$class_item.'"><i class="icon-pencil"></i></a>
                                        <a class="'.$class_delete.'" href="#" data-path="'.$file["base_path"].'" data-type="'.$class_item.'"><i class="icon-trash"></i></a>
                                    </div>
                            </div>';
                     }   
                }
        
            } else {
    
                $html .= 'No files or folders.';
                
            }
            
            $this->results = $html;
        
    }
    
    
    
    
    
    /**
     * edit_image function.
     * 
     * @access private
     * @return html
     */
    private function edit_image(){
        
        $path = htmlentities($_POST['path']);
        
        $html .= '
        <div id="edit-image-wrap">
            <img src="'.MEDIA_LOCATION_URL.$path.'" alt="" id="edit-image">
            <input type="hidden" id="crop-path"  value="'.$path.'">
        </div>
        
        <script>
        $(function() {
        
            var crop_x = "";
            var crop_y = "";
            var crop_x2 = "";
            var crop_y2 = "";
            var crop_width = "";
            var crop_height = "";
            
            $("#crop-image-button").click(function(e){
                e.preventDefault();
                
                $("#edit-image").Jcrop({
        				onChange: showCoords,
        				onSelect: showCoords,
        				minSize: [ 0, 0 ],
        				maxSize: [ 350, 350 ]
                });
    
            
            });
            
            function showCoords(c){
            	
            	crop_x = c.x;
            	crop_y = c.y;
            	crop_x2 = c.x2;
            	crop_y2 = c.y2;
            	
            	crop_width = c.w;
            	crop_height = c.h;
            	
            	$("#w").html(crop_width);
            	$("#h").html(crop_height);
        	};
        	
        	$("#save-crop-image-button").click(function(e){
                e.preventDefault();
                
                var crop_path = $("#crop-path").val();
                
                $.post("index.php?action=CROP_IMAGE", { w: crop_width, h: crop_height, x: crop_x, y: crop_y, x2: crop_x2, y2: crop_y2, path: crop_path }, function(data){ 
                }).success(function(data){
                
                    var obj = jQuery.parseJSON(data);
                    
                    $("#file-to-edit").html(\'Crop saved.<p><img src="\'+obj.cropped_image+\'"></p>\');
                    var curr_location = $("#current-location").val();
                    path = "/" + curr_location;
                    load_files(path);
                                                             
                })
                .error(function(){  
                })
                .complete(function() { 
                });
                
            
            });
        
        });
        </script>';
        
        $this->results = $html;
        
    }
    
    
    
    
    
    /**
     * crop_image function.
     * 
     * @access private
     * @return json
     */
    private function crop_image(){
        
        $cropped_image = $this->FileManager->save_crop();
        $this->results = '{"success":"success", "cropped_image":"'.$cropped_image.'"}';
        
    }
    
    
    
    
    /**
     * image_options function.
     * 
     * @access private
     * @return html
     */
    private function image_options(){
        
        $image_options = $this->FileManager->image_options();
    
        list($width, $height, $type, $attr) = getimagesize($image_options['thumb']['path']);
        $html = '<p><img src="'.$image_options['thumb']['url'].'" width="'.$width.'" height="'.$hieght.'" alt=""></p>';
        
        $html .= '<table class="table images-options-list">';
            $html .= '<thead>
                    <tr><th>Size Type</th><th>Width</th><th>Height</th><th></th></tr>
                </thead>
                <tbody>';
            
            // Original image
            list($width, $height, $type, $attr) = getimagesize($image_options['orig']['path']);
            $html .= '<tr><td><a class="image-option-item" href="#" data-url="'.$image_options['orig']['url'].'"><i class="icon-external-link"></i> Original</a></td><td>'.$width.'px</td><td>'.$height.'px</td><td></td></tr>';
            
            // Custom sizes
            $sizes = $image_options['sizes'];
            if(count($sizes)>0){
                
                foreach($sizes as $size){
                    
                    list($width, $height, $type, $attr) = getimagesize($size['path']);
                    $html .= '<tr><td><a class="image-option-item" href="#" data-url="'.$size['url'].'"><i class="icon-external-link"></i> Custom Size</a></td><td>'.$width.'px</td><td>'.$height.'px</td><td></td></tr>';
                    
                }
                
            }
            
            // Cropped sizes
            $crops = $image_options['crops'];
            if(count($crops)>0){
                
                foreach($crops as $crop){
                    
                    list($width, $height, $type, $attr) = getimagesize($crop['path']);
                    $html .= '<tr><td><a class="image-option-item" href="#" data-url="'.$crop['url'].'"><i class="icon-external-link"></i> Cropped</a></td><td>'.$width.'px</td><td>'.$height.'px</td><td></td></tr>';
                    
                }
                
            }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        $this->results = $html;
        
    }
    
    
    
    
    /**
     * create_folder function.
     * 
     * @access private
     * @return void
     */
    private function create_folder(){
        
        $this->FileManager->create_folder();
        $this->results = '';
        
    }
    
    
    
    
    /**
     * delete_folder function.
     * 
     * @access private
     * @return void
     */
    private function delete_folder(){
        
        $path = htmlentities($_POST['path']);
        $this->FileManager->delete_folder2(MEDIA_LOCATION.$path);
        
        $this->results = '';
        
    }
    
    
    
    
    /**
     * upload_file function.
     * 
     * @access private
     * @return void
     */
    private function upload_file(){
        
        $this->FileManager->upload_file();
        $this->results = '{"success":"success"}';
        
    }
    
    
}
?>