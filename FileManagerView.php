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
        
        $order_by = $_SESSION['order_by'];
        $order_type = ($_SESSION['order_type'])? $_SESSION['order_type'] : false ;
                
        if($order_by){
        
            if($order_by == "name" && $order_type == 'asc'){
            
                array_multisort((array) $names, SORT_ASC, (array) $types, SORT_ASC, $files);
            }
            
            if($order_by == "type" && $order_type == 'asc'){
                
                array_multisort((array) $types, SORT_ASC, (array) $names, SORT_ASC, $files);
            }
            
            if($order_by == "filesize" && $order_type == 'asc'){
                array_multisort((array) $size, SORT_ASC, (array) $types, SORT_ASC, $files);
            }
            
            //// DESC ////////
            if($order_by == "name" && $order_type == 'desc'){
            
                array_multisort((array) $names, SORT_DESC, (array) $types, SORT_DESC, $files);
            }
            
            if($order_by == "type" && $order_type == 'desc'){
                
                array_multisort((array) $types, SORT_DESC, (array) $names, SORT_DESC, $files);
            }
            
            if($order_by == "filesize" && $order_type == 'desc'){
                array_multisort((array) $size, SORT_DESC, (array) $types, SORT_DESC, $files);
            }
            
            
        //array_multisort($types, SORT_ASC, $size, SORT_ASC, $files);
        } else {
            
            array_multisort((array) $types, SORT_ASC, (array) $names, SORT_ASC, $files);
        
        }
        
        
        // Pagnation setup
        $limit = PAGINATE_LIMIT;
	
    	$page = (int) $_GET['page'];
    	if($page){
    		$start = ($page - 1) * $limit;
    	}else{
    		$start = 0;
    	}
    	
        
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
                  
                  $html .= '<div id="pagination-options-wrap">'; 
                
                     $sel1 = ($order_by == 'type')? 'selected="selected"' : '';
                     $sel2 = ($order_by == 'name')? 'selected="selected"' : '';
                     $sel3 = ($order_by == 'filesize')? 'selected="selected"' : '';
                     $sel4 = ($order_type == 'asc')? 'selected="selected"' : '';
                     $sel5 = ($order_type == 'desc')? 'selected="selected"' : '';
                     
                     if(!$order_by){
                         $sel1 = 'selected="selected"';
                     }
                    
                    $html .= '<select id="rs-order-by">';
                        $html .= '<option value="type" '.$sel1.'>Type</option>';
                        $html .= '<option value="name" '.$sel2.'>Name</option>';
                        $html .= '<option value="filesize" '.$sel3.'>Filesize</option>';
                    $html .= '</select> ';
                    
                    $html .= '<select id="rs-order-type">';
                        $html .= '<option value="asc" '.$sel4.'>Asc</option>';
                        $html .= '<option value="desc" '.$sel5.'>Desc</option>';
                    $html .= '</select>';
                    
                    $html .= '<a class="button small list-view-button"><i class="'.$list_icon.'"></i></a>';
                
                $html .= '</div>';
                
                // Pagination
                if(PAGINATE == "ON"){
                    $total_pages = count($files);
                    $files = array_slice($files, $start, PAGINATE_LIMIT);
                    $pagination = $this->paginate("/", $total_pages, PAGINATE_LIMIT);
                    $html .= '<div class="paginate">'.$pagination.'</div>';
                }
        
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
    
    
    
    
    private function paginate($targetpage, $total_pages, $limit = 25){
	
    	$stages = 1;
    	
    	$page = (int) htmlentities($_GET['page']);
    	if($page){
    		$start = ($page - 1) * $limit;
    	}else{
    		$start = 0;
    	}
    	
    	// Initial page num setup
    	if ($page == 0){$page = 1;}
    	$prev = $page - 1;
    	$next = $page + 1;
    	$lastpage = ceil($total_pages/$limit);
    	$LastPagem1 = $lastpage - 1;					
    
    	$paginate = '';
    	if($lastpage > 1)
    	{	
    
    		//$paginate .= "<div class='paginate'>";
    		// Previous
    		if ($page > 1){
    			$paginate.= "<a class='prev' href='$targetpage?page=$prev' data-page='$prev'>&laquo;</a>";
    		}else{
    			$paginate.= "<span class='disabled'>&laquo;</span>";	}
    
    		// Pages
    		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
    		{
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page){
    					$paginate.= "<span class='current'>$counter</span>";
    				}else{
    					$paginate.= "<a href='$targetpage?page=$counter' data-page='$counter'>$counter</a>";}
    			}
    		}
    		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
    		{
    			// Beginning only hide later pages
    			if($page < 1 + ($stages * 2))
    			{
    				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
    				{
    					if ($counter == $page){
    						$paginate.= "<span class='current'>$counter</span>";
    					}else{
    						$paginate.= "<a href='$targetpage?page=$counter' data-page='$counter'>$counter</a>";}
    				}
    				$paginate.= "<span class='adj'>...</span>";
    				$paginate.= "<a href='$targetpage?page=$LastPagem1' data-page='$LastPagem1'>$LastPagem1</a>";
    				$paginate.= "<a href='$targetpage?page=$lastpage' data-page='$lastpage'>$lastpage</a>";
    			}
    			// Middle hide some front and some back
    			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
    			{
    				$paginate.= "<a href='$targetpage?page=1' data-page='1'>1</a>";
    				$paginate.= "<a href='$targetpage?page=2' data-page='2'>2</a>";
    				$paginate.= "<span class='adj'>...</span>";
    				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
    				{
    					if ($counter == $page){
    						$paginate.= "<span class='current'>$counter</span>";
    					}else{
    						$paginate.= "<a href='$targetpage?page=$counter' data-page='$counter'>$counter</a>";}
    				}
    				$paginate.= "<span class='adj'>...</span>";
    				$paginate.= "<a href='$targetpage?page=$LastPagem1' data-page='$LastPagem1'>$LastPagem1</a>";
    				$paginate.= "<a href='$targetpage?page=$lastpage' data-page='$lastpage'>$lastpage</a>";
    			}
    			// End only hide early pages
    			else
    			{
    				$paginate.= "<a href='$targetpage?page=1' data-page='1'>1</a>";
    				$paginate.= "<a href='$targetpage?page=2' data-page='2'>2</a>";
    				$paginate.= "<span class='adj'>...</span>";
    				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page){
    						$paginate.= "<span class='current'>$counter</span>";
    					}else{
    						$paginate.= "<a href='$targetpage?page=$counter' data-page='$counter'>$counter</a>";}
    				}
    			}
    		}
    
    				// Next
    		if ($page < $counter - 1){
    			$paginate.= "<a class='next' href='$targetpage?page=$next' data-page='$next'>&raquo;</a>";
    		}else{
    			$paginate.= "<span class='disabled'>&raquo;</span>";
    			}
    
    		//$paginate.= "</div>";
    	}
    		
    	return $paginate;	
    	
    } // END pagination
    
    
    
    
    /**
     * array_sort function.
     * 
     * @access private
     * @param mixed $a
     * @param mixed $subkey
     * @param mixed $order (default: SORT_ASC)
     * @return array
     */
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