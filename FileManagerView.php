<?php
class FileManagerView{

public $results = '';

private $FileManager;
private $_doc_root;
private $_file_type;
private $_location_url;
    
    function __construct($FileManager){
        
       $this->FileManager = $FileManager;
        
       $fileType = $_SESSION['file_type'];
        
        switch ($fileType) {
            case "images":
                $this->_file_type = '/images';
                break;
            case "files":
                $this->_file_type = '/files';
                break;
            default:
                $this->_file_type = '';
        }
        
        $this->_doc_root = MEDIA_LOCATION.$this->_file_type; 
        $this->_location_url = MEDIA_LOCATION_URL.$this->_file_type;
        
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
            case "ROTATE_IMAGE":
                $this->rotate_image();
                break;    
            case "CREATE_FOLDER":
                $this->create_folder();
                break;
            case "EDIT_FOLDER":
                $this->edit_folder();
                break;
            case "DELETE_FOLDER":
                $this->delete_folder();
                break;
            case "DELETE_CUSTOM_IMAGE":
                $this->delete_custom_image();
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
     * file_types function.
     * 
     * @access private
     * @return array
     */
    private function icon_types(){
        
        $types = array("css"=>"css.png"
                        , "less"=>"css.png"
                        , "scss"=>"css.png"
                        , "sass"=>"css.png"
                        , "html"=>"html.png"
                        , "txt"=>"text.png"
                        , "rtf"=>"text.png"
                        , "doc"=>"word.png"
                        , "docx"=>"word.png"
                        , "pages"=>"pages.png"
                        , "xls"=>"excel.png"
                        , "xlsx"=>"excel.png"
                        , "numbers"=>"number.png"
                        , "pdf"=>"pdf.png"
                        , "zip"=>"compressed.png"
                        , "rar"=>"compressed.png"
                        , "tar"=>"compressed.png"
                        , "ai"=>"illustrator.png"
                        , "psd"=>"photoshop.png"
                        , "ppt"=>"powerpoint.png"
                        , "pptx"=>"powerpoint.png"
                        , "mp3"=>"music.png"
                        , "m4a"=>"music.png"
                        , "ogg"=>"music.png"
                        , "oga"=>"music.png"
                        , "webma"=>"music.png"
                        , "wav"=>"music.png"
                        , "avi"=>"movie.png"
                        , "mp4"=>"movie.png"
                        , "mpg"=>"movie.png"
                        , "mpeg"=>"movie.png"
                        , "flv"=>"movie.png"
                        , "ogv"=>"movie.png"
                        );
        
        return $types;
        
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
        
        foreach ($files as $key => $row) {
            $types[$key] = $row['file_type'];
            $names[$key] = $row['name'];
            $size[$key] = $row['size'];
        }
        
        array_multisort((array) $types, SORT_ASC, (array) $names, SORT_ASC, $files);
        //array_multisort($types, SORT_ASC, $size, SORT_ASC, $files);
        
        $list = ($_SESSION['list'] === true)? 'list' : '';
        $list_icon = ($_SESSION['list'] === true)? 'icon-th-list' : 'icon-th';
        
        // BREADCRUMBS
        $html =  '<div id="bread-wrap">
                    <div class="bread-wrap-inner">';
        
        if(strlen($path)){
            $html .=  '&laquo; <a class="bread-path" href="#" data-path="'.$bread_path.'">Back</a> | <a href="#" class="bread-path" data-path="">Home</a> / '.$bread_crumb;
        } else {
            $html .=  'Home';
        }
        
                    $html .= '<a class="button small list-view-button"><i class="'.$list_icon.'"></i></a>';
        
        $html .=  '</div>
                </div>';
        
                
        $html .=  '<input type="hidden" name="current_location" id="current-location" value="'.$path.'">';
        
        if(count($files)>0){
                
                    
                // List folders
                foreach($files as $file){
                        
                    if($file['file_type'] == 'dir'){            
            
                        $class_item = "dir";
                        $class_delete = "delete-folder";
                        $class_edit = "edit-dir"; 
            
                        
                        $html .=  '<div class="grid-item '.$list.' '.$class_item.'">
                                    <a class="folder" href="#" data-path="'.$file["base_path"].'" alt="'.$file["name"].'" title="'.$file["name"].'">
                                        <img src="images/folder.png" width="64" height="64" alt="folder">
                                        <br>
                                        <span class="file-name">'.$file["name"].'</span>
                                    </a>
                                    <div class="file-actions">
                                        <a class="'.$class_edit.'" href="#" data-path="'.$file["base_path"].'" data-type="'.$class_item.'"><i class="icon-pencil"></i></a>
                                        <a class="'.$class_delete.'" href="#" data-path="'.$file["base_path"].'" data-type="'.$class_item.'"><i class="icon-trash"></i></a>
                                    </div>
                            </div>';
                    }
               // }
                
                // List Files
                   
                   if($file['file_type'] == 'file'){
                        
                        $class_item = "file";
                        $class_delete = "delete-file";
                        $class_edit = "edit-file";
                        
                        $allowed_img = array("image/jpg"=>"jpg", "image/jpeg"=>"jpeg", "image/png"=>"png", "image/gif"=>"gif");
                        
                        $html .=  '<div class="grid-item '.$list.' '.$class_item.'">';
                        
                        if(file_exists($this->_doc_root.$file["base_path_thumb"])){
                            $attr = getimagesize($this->_doc_root.$file["base_path_thumb"]);
                        }
                        $mime = $attr['mime'];
                        
                        
                        if(array_key_exists($mime,$allowed_img)) {
                        
                            $class_link = "view-img-sibs";
                            
                        
                            $html .=  '      <a class="'.$class_link.'" href="#" data-path="'.$file["base_path"].'" alt="'.$file["name"].'" title="'.$file["name"].'">
                                            <img src="'.$this->_location_url.$file["base_path_thumb"].'" '.$attr[3].' class="max-width">
                                            <br>
                                            <span class="file-name">'.$file["name"].'</span>
                                        </a>';
                        
                            $html .=  '      <div class="file-actions">
                                        <a class="'.$class_edit.'" href="#" data-path="'.$file["base_path"].'" data-type="'.$class_item.'"><i class="icon-pencil"></i></a>
                                        <a class="'.$class_delete.'" href="#" data-path="'.$file["base_path"].'" data-type="'.$class_item.'"><i class="icon-trash"></i></a>
                                    </div>
                            </div>';            
                        
                        } else {
    
                            
                            $html .=  '      <a class="file file-option-item" href="#" data-path="'.$this->_location_url.$file["url_path"].'" alt="'.$file["name"].'" title="'.$file["name"].'">';
                                    
                                    $path_info = pathinfo($file['abs_path']);
                                    $icon_types = $this->icon_types();
                                    
                                    $icon_type = (array_key_exists($path_info["extension"], $icon_types))? $icon_types[$path_info["extension"]] : 'fileicon_bg.png' ;
                                    
                            
                                    $html .= '<img src="images/'.$icon_type.'" width="64" height="64" alt="'.$path_info["filename"].'.'.$path_info["extension"].'">';
                            
                            $html .=  '       <br>
                                            <span class="file-name">'.$file["name"].'</span>
                                        </a>';
                                        
                            $html .=  '      <div class="file-actions">
                                        <a class="'.$class_delete.'" href="#" data-path="'.$file["base_path"].'" data-type="'.$class_item.'"><i class="icon-trash"></i></a>
                                    </div>
                            </div>';            
                            
                        }
                        
                        
                     } // END if file_type  
                }
        
            } else {
    
                $html .= '<div class="notify message core-notify">
            <div class="notify-inner">
                <p>No files or folders</p>    
            </div>
        </div>';
                
            }
            
            $this->results = $html;
        
    }
    
    
    
    
    private function array_sort($a, $subkey, $order=SORT_ASC) {
        
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
    
    
    
    
    /**
     * edit_image function.
     * 
     * @access private
     * @return html
     */
    private function edit_image(){
        
        $path = htmlentities($_POST['path']);
        
        // Get image size
        list($width, $height, $type, $attr) = getimagesize($this->_location_url.$path);
        
        $html .= '
        <div id="edit-image-wrap">
            <img src="'.$this->_location_url.$path.'?t='.microtime().'" width="'.$width.'" height="'.$height.'" alt="" id="edit-image">
            <input type="hidden" id="crop-path"  value="'.$path.'">
        </div>';
        
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
        $this->results = '{"success":"success", "cropped_image":"'.$cropped_image['url_path'].'", "path":"'.$cropped_image['path'].'"}';
        
    }
    
    
    
    
    /**
     * rotate_image function.
     * 
     * @access private
     * @return void
     */
    private function rotate_image(){
        
        $this->FileManager->rotate_image();
        
    }
    
    
    
    
    /**
     * image_options function.
     * 
     * @access private
     * @return html
     */
    private function image_options(){
        
        $image_options = $this->FileManager->image_options();
    
        //list($width, $height, $type, $attr) = getimagesize($image_options['thumb']['path']);
        //$html = '<p><img src="'.$image_options['thumb']['url'].'" width="'.$width.'" height="'.$hieght.'" alt=""></p>';
        
        $html = '<table class="table images-options-list condensed">';
            $html .= '<thead>
                    <tr><th>Size Type</th><th></th><th>Width</th><th>Height</th><th></th></tr>
                </thead>
                <tbody>';
            
            // Original image
            if(file_exists($image_options['orig']['path'])){
                list($width, $height, $type, $attr) = getimagesize($image_options['orig']['path']);
                $html .= '<tr>
                        <td><a href="#" class="edit-file-option" data-path="'.$image_options['orig']['local_path'].'"><i class="icon-eye-open"></i> Original</a></td>
                        <td><a class="image-option-item" href="#" data-url="'.$image_options['orig']['url'].'"><i class="icon-external-link"></i></a></td>
                        <td>'.$width.'px</td>
                        <td>'.$height.'px</td>
                    <td></td>
                    </tr>';
            }
            
            // Custom sizes
            $sizes = $image_options['sizes'];
            if(count($sizes)>0){
                
                foreach($sizes as $size){
                    
                    if(file_exists($size['path'])){
                        list($width, $height, $type, $attr) = getimagesize($size['path']);
                        $html .= '<tr>
                                    <td><a href="#" class="edit-file-option" data-path="'.$size['local_path'].'"><i class="icon-eye-open"></i> Custom Size</a></td>
                                    <td><a class="image-option-item" href="#" data-url="'.$size['url'].'"><i class="icon-external-link"></i></a></td>
                                    <td>'.$width.'px</td>
                                    <td>'.$height.'px</td>
                                    <td><a class="delete-custom-image" href="#" data-path="'.$size['local_path'].'"><i class="icon-trash"></i></a></td>
                                </tr>';
                    }
                    
                }
                
            }
            
            // Cropped sizes
            $crops = $image_options['crops'];
            if(count($crops)>0){
                
                foreach($crops as $crop){
                    
                    if(file_exists($crop['path'])){
                        list($width, $height, $type, $attr) = getimagesize($crop['path']);
                        $html .= '<tr>
                                    <td><a href="#" class="edit-file-option" data-path="'.$crop['local_path'].'"><i class="icon-eye-open"></i> Cropped</a></td>
                                    <td><a class="image-option-item" href="#" data-url="'.$crop['url'].'"><i class="icon-external-link"></i></a></td>
                                    <td>'.$width.'px</td>
                                    <td>'.$height.'px</td>
                                    <td><a class="delete-custom-image" href="#" data-path="'.$crop['local_path'].'"><i class="icon-trash"></i></a></td>
                                </tr>';
                    }
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
    
    
    
    public function edit_folder(){
        
        $this->FileManager->edit_folder();
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
        $this->FileManager->delete_folder($this->_doc_root.$path);
        
        $this->results = '';
        
    }
    
    
    
    
    /**
     * delete_custom_image function.
     * 
     * @access private
     * @return void
     */
    private function delete_custom_image(){
        
        $path = htmlentities($_POST['path']);
        $this->FileManager->delete_custom_image($this->_doc_root.$path);
        $this->results = '';
        
    }
    
    
    
    
    /**
     * upload_file function.
     * 
     * @access private
     * @return void
     */
    private function upload_file(){
        
        if($this->FileManager->upload_file()){
            $this->results = '{"success":"success"}';
        } else {
            $this->results = '{"error": {"message": "'.$this->FileManager->error[0].'"} }';
        }
        
    }
    
    
}
?>