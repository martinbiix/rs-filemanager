<?php
class FileManager{

public $error = array();
public $doc_types = array();
public $img_sizes = array();
public $img_types = array();

private $_location;
private $_file_type;
private $_path;
    
    function __construct(){
    
        // Check for FM files location. Throw error if not found. Create files and images folder if not sub folders.
        if(!is_dir(MEDIA_LOCATION)){
            echo 'Media folder could not be found. Please check your configuration.';
        }
        
        $this->_location = dirname(__FILE__);
        
        $fileType = htmlentities($_GET['file_type']);
        
        switch ($fileType) {
            case "images":
                $this->_file_type = '/images';
                break;
            default:
                $this->_file_type = '/files';
        }
        
        if(isset($_POST['path'])){
            $this->_path = htmlentities($_POST['path']);
        }
             
    }
    
    
    
    
    private function return_header($type, $mess="Error"){
        
        if($type == "ERROR" ){
            header("HTTP/1.0 409 ".$mess);
            exit();
        }
        
        
    }
    
    
    
    
    /**
     * dir_list function.
     * 
     * @access public
     * @param bool $path (default: false)
     * @return array
     */
    public function dir_list($folders_only = false){
        
        $files = array();
        
        if ($handle = opendir(MEDIA_LOCATION.$this->_path)) {
        
            $blacklist = array('.', '..', '.DS_Store', '_thumbs', '_sizes', '_crops', '.svn', '.git');
            $i=0;
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, $blacklist) &&  ( ($folders_only && is_dir(MEDIA_LOCATION.$this->_path.'/'.$file) ) || (!$folders_only) )  ) {
                    
                    $pathinfo = pathinfo(MEDIA_LOCATION.'/'.$this->_path.'/'.$file);
                    
                    $files[$i]['name'] = $file;
                    $files[$i]['url_path'] = str_replace($_SERVER['DOCUMENT_ROOT'],"",MEDIA_LOCATION.$this->_path.'/'.$file);
                    $files[$i]['thumb_path'] = str_replace($_SERVER['DOCUMENT_ROOT'],"",MEDIA_LOCATION.$this->_path.'/_thumbs/'.$file);
                    $files[$i]['abs_path'] = MEDIA_LOCATION.$this->_path.'/'.$file;
                    $files[$i]['ext'] = strtolower($pathinfo['extension']);
                    $files[$i]['file_type'] = filetype(MEDIA_LOCATION.$this->_path.'/'.$file);
                    $files[$i]['base_path'] = str_replace(MEDIA_LOCATION, "", MEDIA_LOCATION.$this->_path.'/'.$file);
                    $files[$i]['base_path_thumb'] = str_replace(MEDIA_LOCATION, "", MEDIA_LOCATION.$this->_path.'/_thumbs/'.$file);
                    $files[$i]['base_name'] = basename(MEDIA_LOCATION.$this->_path.'/'.$file);
                    $i++;
                }
            }
        
            closedir($handle);
        
        }
        
        return $files;
        
    }
    
    
    
    /**
     * bread_path function.
     * 
     * @access public
     * @return string
     */
    public function bread_path(){
        
        $path = ltrim($this->_path, "/");
        $path_pieces = explode("/",$path);
        
        $count = count($path_pieces);
        
        $last = array_pop($path_pieces);
        
        $bread_path = "";
        foreach($path_pieces as $piece){
        
            $bread_path .= '/'.$piece;
        
        }
        
        $bread_path = (strlen($bread_path))? $bread_path : '';
        
        return $bread_path;
        
    }
    
    
    
    /**
     * path function.
     * 
     * @access public
     * @return string
     */
    public function path(){
        
         $path = ltrim($this->_path, "/");
         
         return $path;
        
    }
    
    
    
    /**
     * current_folder function.
     * 
     * @access public
     * @return string
     */
    public function current_folder(){
        
        $path = ltrim($this->_path, "/");
        $path_pieces = explode("/",$path);
        $last = array_pop($path_pieces);
        
        return $last;
        
    }
    
    
    
    /**
     * bread_crumb function.
     * 
     * @access public
     * @return string
     */
    public function bread_crumb(){
        
        $path = ltrim($this->_path, "/");
        $path_pieces = explode("/",$path);
        
        $count = count($path_pieces);
        
        $last = array_pop($path_pieces);
        
        $bread_path = "";
        foreach($path_pieces as $piece){
            
            $piece_url .= '/'.$piece;
            $bread_path .= '<a class="bread-path" href="#" data-path="'.$piece_url.'">'.$piece.'</a> / ';
        
        }
        
        $bread_path = $bread_path.$last;
        
        return $bread_path;

        
    }
    
    
    
    /**
     * create_folder function.
     * 
     * @access public
     * @return void
     */
    public function create_folder(){
        
        $new_folder_name = $this->clean_foldername($_POST['new_folder']);
        
        // Check if folder name already exists
        if(is_dir(MEDIA_LOCATION.'/'.$new_folder_name)){
            $this->return_header('ERROR', "Folder already exists");
            return false;
        }
        
        // Need to prevent creating thumbs and sizes folders and need to clean folder names
        mkdir(MEDIA_LOCATION.$this->_path.'/'.$new_folder_name, 0775, false);
        /*mkdir(MEDIA_LOCATION.$this->_path.'/'.$new_folder_name.'/_thumbs', 0775, false);
        mkdir(MEDIA_LOCATION.$this->_path.'/'.$new_folder_name.'/_sizes', 0775, false);
        mkdir(MEDIA_LOCATION.$this->_path.'/'.$new_folder_name.'/_crops', 0775, false);*/
        
    }
    
    
    
    
    /**
     * edit_folder function.
     * 
     * @access public
     * @return void
     */
    public function edit_folder(){
        
        $clean_name = $this->clean_foldername($_POST['folder_name']);
        
        // Check if folder name already exists
        if(is_dir(MEDIA_LOCATION.'/'.$clean_name)){
            $this->return_header('ERROR', "Folder already exists");
            return false;
        }
        
        // Rename folder new folder
        rename(MEDIA_LOCATION.$this->_path, MEDIA_LOCATION.'/'.$clean_name); 
        
    }
    
    
    
    /**
     * delete_folder function.
     * 
     * @access public
     * @return void
     */
    public function delete_folder($directory, $empty=FALSE){
        
        $file_type = htmlentities($_POST['file_type']);
        
        if($file_type == 'dir'){
        
            if(substr($directory,-1) == '/'){
    		$directory = substr($directory,0,-1);
        	}
        	if(!file_exists($directory) || !is_dir($directory)){
        		return FALSE;
        	}elseif(!is_readable($directory)){
        		return FALSE;
        	}else{
        		$handle = opendir($directory);
        		while (FALSE !== ($item = readdir($handle))){
        			if($item != '.' && $item != '..'){
        				$path = $directory.'/'.$item;
        				if(is_dir($path)) {
        					$this->delete_folder($path);
        				}else{
        					unlink($path);
        				}
        			}
        		}
        		closedir($handle);
        		if($empty == FALSE){
        			if(!rmdir($directory)){
        				return FALSE;
        			}
        		}
        		return TRUE;
        	}
        	
        } elseif ($file_type == "file") {
         
            // Get file name w/o extension
            $filename = basename($directory);
            // Get extension
            $ext = $this->getExtension($filename);
            // Get name
            $name = str_replace('.'.$ext, "", $filename);
            // Get path to file
            $path = str_replace($filename, "", $directory);
            
            
            // Remove root file
            unlink($directory);
            
            $allowed_img = array("image/jpg"=>"jpg", "image/jpeg"=>"jpeg", "image/png"=>"png", "image/gif"=>"gif");
            // If Image file type
            if(in_array($ext, $allowed_img)){
            
                // Remove thumbs
                unlink($path.'_thumbs/'.$name.'.'.$ext);
                
                // Remove sizes
                $possibleFiles = glob($path.'_sizes/'.$name.'_*.'.$ext);
                foreach ($possibleFiles as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
                // Remove crops
                $possibleFiles = glob($path.'_crops/'.$name.'_*.'.$ext);
                foreach ($possibleFiles as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            
            } // END IF
            
             
        }
        
    }
    
    
    
    
    /**
     * save_crop function.
     * 
     * @access public
     * @return string
     */
    public function save_crop(){
        
        $file = $this->_path;
        
        $ran = $this->randString(6);
        
        // Get file name w/o extension
        $filename = basename($file);
        // Get extension
        $ext = $this->getExtension($filename);
        // Get name
        $name = str_replace('.'.$ext, "", $filename);
        // Get path to file
        $path = str_replace($filename, "", $file);
        
        // If crops folder does not exist create it
        if(!is_dir(MEDIA_LOCATION.$path.'_crops')){
            mkdir(MEDIA_LOCATION.$path.'_crops', 0775, false);
        }
        
        $output_filename = MEDIA_LOCATION.$path.'_crops/'.$name.'_'.$ran.'.'.$ext;
        
        //echo $output_filename;
        
        $x = (int) $_POST['x'];
        $y = (int) $_POST['y'];
        $x2 = (int) $_POST['x2'];
        $y2 = (int) $_POST['y2'];
        $w = (int) $_POST['w'];
        $h = (int) $_POST['h'];
        
        //echo 'X:'.$x.' Y:'.$y.' X2:'.$x2.' Y2:'.$y2.' W:'.$w.' H:'.$h;
        
        $targ_w = $targ_h = 150;
        $jpeg_quality = 90;
        
        $src = MEDIA_LOCATION.$this->_path;
        
        //echo $src;
        
        $img_r = imagecreatefromjpeg($src);
        $dst_r = ImageCreateTrueColor( $w, $h );
        
        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $w, $h, $w, $h);
        
        imagejpeg($dst_r, $output_filename, $jpeg_quality);
        
        return MEDIA_LOCATION_URL.$path.'_crops/'.$name.'_'.$ran.'.'.$ext;
        
    }
    
    
    
    /**
     * image_options function.
     * 
     * @access public
     * @return array
     */
    public function image_options(){
        
        $file = $this->_path;
        
        // Get file name w/o extension
        $filename = basename($file);
        // Get extension
        $ext = $this->getExtension($filename);
        // Get name
        $name = str_replace('.'.$ext, "", $filename);
        // Get path to file
        $path = str_replace($filename, "", $file);
        
        $arr = array();
        
        $arr['thumb']['path'] = MEDIA_LOCATION.$path.'_thumbs/'.$name.'.'.$ext;
        $arr['thumb']['url'] = MEDIA_LOCATION_URL.$path.'_thumbs/'.$name.'.'.$ext;
        $arr['orig']['path'] = MEDIA_LOCATION.$file;
        $arr['orig']['url'] = MEDIA_LOCATION_URL.$file; 
        
        $possibleSizes = glob(MEDIA_LOCATION.$path.'_sizes/'.$name.'_*.'.$ext);
            $i=0;
            foreach ($possibleSizes as $file) {
                if (file_exists($file)) {
                    $arr['sizes'][$i]['path'] = $file;
                    $arr['sizes'][$i]['url'] = MEDIA_LOCATION_URL.str_replace(MEDIA_LOCATION, '', $file);
                    $i++;
                }
            }
            
        $possibleCrops = glob(MEDIA_LOCATION.$path.'_crops/'.$name.'_*.'.$ext);
            $k=0;
            foreach ($possibleCrops as $file) {
                if (file_exists($file)) {
                    $arr['crops'][$k]['path'] = $file;
                    $arr['crops'][$k]['url'] = MEDIA_LOCATION_URL.str_replace(MEDIA_LOCATION, '', $file);
                    $k++;
                }
            }    
             
        return $arr;   
        
    }
    
    
    
    /**
     * upload_file function.
     * 
     * @access public
     * @return void
     */
    public function upload_file(){
        
        $allowed = array("image/jpg"=>"jpg", "image/jpeg"=>"jpeg", "image/png"=>"png", "image/gif"=>"gif");
        
        $custom_width = $_REQUEST['custom_width'];
        $custom_height = $_REQUEST['custom_height'];
        
        if($custom_width || $custom_height){
            
            // Insert custom size to image_sizes array
            $custom_array = array(array("width" => $custom_width , "height" => $custom_height));
            $this->img_sizes = array_merge($this->img_sizes, $custom_array);
        
        }
		
        if($_FILES['file']['size'] > 0){
		
			$tempFile = $_FILES['file']['tmp_name'];
			$origName = $_FILES['file']['name'];
			$fileSize = $_FILES['file']['size'];
			
			$cleanName = $this->clean_filename($origName);
			
			// Get file size
			$theDiv = $fileSize / (MAX_UPLOAD_SIZE * 100000);
			// MB
			$theFileSize = round($theDiv, 1);
			// Get mime type
			$attr = getimagesize($tempFile);
			$mime = $attr['mime'];
			
			// Validate file upload
			if($theFileSize > MAX_UPLOAD_SIZE){ 
    			$this->error[] = "The file uploaded is over ".MAX_UPLOAD_SIZE."MB.";
    			return false;
			}
			
			if(!array_key_exists($mime,$allowed)) {
    			//$this->error[] = "Invalid file type.";
    			//return false;
			}

			if(file_exists(MEDIA_LOCATION.'/'.$this->_path.'/'.$cleanName)){
    			$this->error[] = $cleanName." already exists. Please delete current file or rename the file your are trying to upload and try again.";
    			return false;
			}

			move_uploaded_file($tempFile, MEDIA_LOCATION.'/'.$this->_path.'/'.$cleanName);
			
			// Get extension
			$ext = $this->getExtension(MEDIA_LOCATION.'/'.$this->_path.'/'.$cleanName);
			// Get orig file dimensions
			list($width, $height, $type, $attr) = getimagesize(MEDIA_LOCATION.'/'.$this->_path.'/'.$cleanName);
			
			// If file is an image file create the thumbnails and custom sizes
			if(in_array($ext, $this->img_types)){
			     
                if(!is_dir(MEDIA_LOCATION.'/'.$this->_path.'/_thumbs')){			     
    			     mkdir(MEDIA_LOCATION.'/'.$this->_path.'/_thumbs', 0775, false);
    			}
    			if(!is_dir(MEDIA_LOCATION.'/'.$this->_path.'/_sizes')){
    			     mkdir(MEDIA_LOCATION.'/'.$this->_path.'/_sizes', 0775, false);
    			}
    			
    			// Create thumbs
    			if($width > THUMB_MAX_WIDTH || $height > THUMB_MAX_HEIGHT){
    			     $this->make_thumb(MEDIA_LOCATION.'/'.$this->_path.'/'.$cleanName, MEDIA_LOCATION.'/'.$this->_path.'/_thumbs/'.$cleanName, THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT);
    			} else {
        			copy(MEDIA_LOCATION.'/'.$this->_path.'/'.$cleanName, MEDIA_LOCATION.'/'.$this->_path.'/_thumbs/'.$cleanName);
    			}
    			
    			$i=1;
    			foreach($this->img_sizes as $size){
    			
        			// Rename file with custom size suffix
        			$coreName = str_replace('.'.$ext, "", $cleanName);
        			$newName = $coreName.'_'.$i.'.'.$ext;
        			
        			if($width > $size['width'] || $height > $size['height']){
        			     $this->make_thumb(MEDIA_LOCATION.'/'.$this->_path.'/'.$cleanName, MEDIA_LOCATION.'/'.$this->_path.'/_sizes/'.$newName, $size['width'], $size['height']);
        			     $i++;
        			}
        			
    			}
			
			}
			
			
        }
        
        return true;
        
    }
    
    
    
    /**
     * make_thumb function.
     * 
     * @access private
     * @param mixed $img_name
     * @param mixed $filename
     * @param mixed $new_w
     * @param mixed $new_h
     * @return void
     */
    private function make_thumb($img_name,$filename,$new_w,$new_h){

		//get image extension.
		$ext = $this->getExtension($img_name);
		//creates the new image using the appropriate function from gd library
		if(!strcmp("jpg",strtolower($ext)) || !strcmp("jpeg",$ext))
		$src_img=imagecreatefromjpeg($img_name);
		
		if(!strcmp("gif",$ext))
		$src_img=imagecreatefromgif($img_name);
		
		if(!strcmp("png",$ext)){
		$src_img=imagecreatefrompng($img_name);
		imagealphablending($src_img, false);
		imagesavealpha($src_img, true);
		}
		
		//gets the dimmensions of the image
		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);
	
		$ratio1=$old_x/$new_w;
		$ratio2=$old_y/$new_h;
		
		if($ratio1>$ratio2) {
		
			$thumb_w=$new_w;
			$thumb_h=$old_y/$ratio1;
		
		} else {
		
			$thumb_h=$new_h;
			$thumb_w=$old_x/$ratio2;
		
		}
	
		// we create a new image with the new dimmensions
		$dst_img = ImageCreateTrueColor($thumb_w,$thumb_h);
		
		// resize the big image to the new created one
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		
		// output the created image to the file. Now we will have the thumbnail into the file named by $filename
		if(!strcmp("gif",$ext))
		imagegif($dst_img,$filename);
		elseif(!strcmp("jpg",strtolower($ext)) || !strcmp("jpeg",$ext))
		imagejpeg($dst_img,$filename);
		elseif(!strcmp("png",$ext))
		imagepng($dst_img,$filename);
		
		//destroys source and destination images.
		imagedestroy($dst_img);
		imagedestroy($src_img);
	
	}
	
	
	
	/**
	 * getExtension function.
	 * 
	 * @access private
	 * @param mixed $str
	 * @return string
	 */
	private function getExtension($str) {
		
		$i = strrpos($str,".");
		
		if (!$i) { return ""; }
			
		$l = strlen($str) - $i;
		
		$ext = substr($str,$i+1,$l);
		
		return $ext;
	}
    
    
    
    /**
     * clean_filename function.
     * 
     * @access private
     * @param mixed $filename
     * @return string
     */
    private function clean_filename($filename){
        
        $filename = preg_replace('/^\W+|\W+$/', '', $filename);
        $filename = preg_replace('/\s+/', '-', $filename);
        $filename = str_replace('_', '-', $filename);
    
        return strtolower(preg_replace('/\W-/', '', $filename));

    }
    
    
    
    
    /**
     * clean_foldername function.
     * 
     * @access private
     * @param mixed $foldername
     * @return string
     */
    private function clean_foldername($filename){
        
        $filename = preg_replace('/^\W+|\W+$/', '', $filename);
        $filename = preg_replace('/\s+/', '_', $filename);
    
        return strtolower(preg_replace('/\W-/', '', $filename));
        
    }
    
    
    
    /**
     * randString function.
     * 
     * @access private
     * @param mixed $length
     * @return string
     */
    private function randString($length = 8){
        
        $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $str = '';
        $count = strlen($charset);
        
        while ($length--) {
            $str .= $charset[mt_rand(0, $count-1)];
            }
        
        return $str;
    
    }
    
    
}
?>