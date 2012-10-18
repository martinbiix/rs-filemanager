<?php
class FileManager{

public $error = array();
public $doc_types = array();
public $img_sizes = array();
public $img_types = array();

private $_location;
private $_file_type;
private $_path;
private $_doc_root;
private $_location_url;
    
    function __construct(){
    
        // Check for FM files location. Throw error if not found. Create files and images folder if not sub folders.
        if(!is_dir(MEDIA_LOCATION)){
            echo 'Uploads folder could not be found. Please check your configuration.';
        }
        
        $this->_location = dirname(__FILE__);
        
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
        
        if ($handle = opendir($this->_doc_root.$this->_path)) {
        
            $blacklist = array('.', '..', '.DS_Store', '_thumbs', '_sizes', '_crops', '.svn', '.git');
            $i=0;
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, $blacklist) &&  ( ($folders_only && is_dir($this->_doc_root.$this->_path.'/'.$file) ) || (!$folders_only) )  ) {
                    
                    $pathinfo = pathinfo($this->_doc_root.'/'.$this->_path.'/'.$file);
                    
                    $files[$i]['name'] = $file;
                    $files[$i]['url_path'] = str_replace($_SERVER['DOCUMENT_ROOT'],"",$this->_doc_root.$this->_path.'/'.$file);
                    $files[$i]['thumb_path'] = str_replace($_SERVER['DOCUMENT_ROOT'],"",$this->_doc_root.$this->_path.'/_thumbs/'.$file);
                    $files[$i]['abs_path'] = $this->_doc_root.$this->_path.'/'.$file;
                    $files[$i]['ext'] = strtolower($pathinfo['extension']);
                    $files[$i]['file_type'] = filetype($this->_doc_root.$this->_path.'/'.$file);
                    $files[$i]['base_path'] = str_replace($this->_doc_root, "", $this->_doc_root.$this->_path.'/'.$file);
                    $files[$i]['base_path_thumb'] = str_replace($this->_doc_root, "", $this->_doc_root.$this->_path.'/_thumbs/'.$file);
                    $files[$i]['base_name'] = basename($this->_doc_root.$this->_path.'/'.$file);
                    $files[$i]['size'] = filesize($this->_doc_root.$this->_path.'/'.$file);
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
        if(is_dir($this->_doc_root.'/'.$new_folder_name)){
            $this->return_header('ERROR', "Folder already exists");
            return false;
        }
        
        // Need to prevent creating thumbs and sizes folders and need to clean folder names
        mkdir($this->_doc_root.$this->_path.'/'.$new_folder_name, 0775, false);
        /*mkdir($this->_doc_root.$this->_path.'/'.$new_folder_name.'/_thumbs', 0775, false);
        mkdir($this->_doc_root.$this->_path.'/'.$new_folder_name.'/_sizes', 0775, false);
        mkdir($this->_doc_root.$this->_path.'/'.$new_folder_name.'/_crops', 0775, false);*/
        
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
        if(is_dir($this->_doc_root.'/'.$clean_name)){
            $this->return_header('ERROR', "Folder already exists");
            return false;
        }
        
        // Rename folder new folder
        rename($this->_doc_root.$this->_path, $this->_doc_root.'/'.$clean_name); 
        
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
     * delete_custom_image function.
     * 
     * @access public
     * @param mixed $file
     * @return void
     */
    public function delete_custom_image($file){
        
        unlink($file);
        
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
        if(!is_dir($this->_doc_root.$path.'_crops')){
            mkdir($this->_doc_root.$path.'_crops', 0775, false);
        }
        
        $cleaned_path = str_replace("/_crops","",$this->_doc_root.$path);
        $cleaned_path = str_replace("/_sizes","",$cleaned_path);
        $output_filename = $cleaned_path.'_crops/'.$name.'_'.$ran.'.'.$ext;
        
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
        
        $src = $this->_doc_root.$this->_path;
        
        //echo $src;
        
        $img_r = imagecreatefromjpeg($src);
        $dst_r = ImageCreateTrueColor( $w, $h );
        
        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $w, $h, $w, $h);
        
        imagejpeg($dst_r, $output_filename, $jpeg_quality);
        
        $cleaned_urlpath = str_replace("/_crops","",$this->_location_url.$path);
        $cleaned_urlpath = str_replace("/_sizes","",$cleaned_urlpath);
        
        $cropped = array();
        $cropped['url_path'] = $cleaned_urlpath.'_crops/'.$name.'_'.$ran.'.'.$ext;
        $cropped['path'] = str_replace($this->_location_url,"",$cleaned_urlpath.'_crops/'.$name.'_'.$ran.'.'.$ext);
        
        return $cropped;
        
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
        
        $arr['thumb']['path'] = $this->_doc_root.$path.'_thumbs/'.$name.'.'.$ext;
        $arr['thumb']['url'] = $this->_location_url.$path.'_thumbs/'.$name.'.'.$ext;
        $arr['orig']['path'] = $this->_doc_root.$file;
        $arr['orig']['local_path'] = $file;
        $arr['orig']['url'] = $this->_location_url.$file; 
        
        $possibleSizes = glob($this->_doc_root.$path.'_sizes/'.$name.'_*.'.$ext);
            $i=0;
            foreach ($possibleSizes as $file) {
                if (file_exists($file)) {
                    $arr['sizes'][$i]['path'] = $file;
                    $arr['sizes'][$i]['local_path'] = str_replace($this->_doc_root, '', $file);
                    $arr['sizes'][$i]['url'] = $this->_location_url.str_replace($this->_doc_root, '', $file);
                    $i++;
                }
            }
            
        $possibleCrops = glob($this->_doc_root.$path.'_crops/'.$name.'_*.'.$ext);
            $k=0;
            foreach ($possibleCrops as $file) {
                if (file_exists($file)) {
                    $arr['crops'][$k]['path'] = $file;
                    $arr['crops'][$k]['local_path'] = str_replace($this->_doc_root, '', $file);
                    $arr['crops'][$k]['url'] = $this->_location_url.str_replace($this->_doc_root, '', $file);
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

			if(file_exists($this->_doc_root.'/'.$this->_path.'/'.$cleanName)){
    			$this->error[] = $cleanName." already exists. Please delete current file or rename the file your are trying to upload and try again.";
    			return false;
			}
			
			$urlandname = $this->_doc_root.'/'.$this->_path.'/'.$cleanName;
			
			move_uploaded_file($tempFile, $urlandname);	
			
			// Get extension
			$ext = $this->getExtension($urlandname);
			
			// If file is an image file create the thumbnails and custom sizes
			if(in_array($ext, $this->img_types)){
			     
			     // Get orig file dimensions
			     list($width, $height, $type, $attr) = getimagesize($urlandname);
			
			     $image = $this->convert_image($urlandname, $mime);

    			
    			if($mime == 'image/pjpeg' || $mime == 'image/jpeg'){
                    imagejpeg ($image,$urlandname,90);
                } elseif($mime == 'image/x-png' || $mime == 'image/png') {
                    imagepng ($image,$urlandname);
                }elseif($mime == 'image/gif'){
                    imagegif ($image,$urlandname);
                }
			     
                if(!is_dir($this->_doc_root.'/'.$this->_path.'/_thumbs')){			     
    			     mkdir($this->_doc_root.'/'.$this->_path.'/_thumbs', 0775, false);
    			}
    			if(!is_dir($this->_doc_root.'/'.$this->_path.'/_sizes')){
    			     mkdir($this->_doc_root.'/'.$this->_path.'/_sizes', 0775, false);
    			}
    
    			// Create thumbs
    			if($width > THUMB_MAX_WIDTH || $height > THUMB_MAX_HEIGHT){
    			     $this->make_thumb($image, $this->_doc_root.'/'.$this->_path.'/_thumbs/'.$cleanName, THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT, $mime);
    			} else {
        			//copy($urlandname, MEDIA_LOCATION.'/'.$this->_path.'/_thumbs/'.$cleanName);
    			}
    			
    			$i=1;
    			foreach($this->img_sizes as $size){
    			
        			// Rename file with custom size suffix
        			$coreName = str_replace('.'.$ext, "", $cleanName);
        			$newName = $coreName.'_'.$i.'.'.$ext;
        			
        			if($width > $size['width'] || $height > $size['height']){
        			     $this->make_thumb($image, $this->_doc_root.'/'.$this->_path.'/_sizes/'.$newName, $size['width'], $size['height'], $mime);
        			     $i++;
        			}
        			
    			}
    			
    			imagedestroy($image);
    			
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
    private function make_thumb($im,$urlandname,$maxwidth,$maxheight, $imagetype){
        
        $width=imageSX($im);
		$height=imageSY($im);
        
        if(($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight)){
        	
        	if($maxwidth && $width > $maxwidth){
        		$widthratio = $maxwidth/$width;
        		$resizewidth=true;
        		} else $resizewidth=false;

        	if($maxheight && $height > $maxheight)
        		{
        		$heightratio = $maxheight/$height;
        		$resizeheight=true;
        		} 
        	else $resizeheight=false;

         	if($resizewidth && $resizeheight)
        		{
        		if($widthratio < $heightratio) $ratio = $widthratio;
        		else $ratio = $heightratio;
        		}
        	elseif($resizewidth)
        		{
        		$ratio = $widthratio;
        		}
        	elseif($resizeheight)
        		{
        		$ratio = $heightratio;
        		}
        	
        	$newwidth = $width * $ratio;
        	$newheight = $height * $ratio;
        	
        		if(function_exists('imagecopyresampled') && $imagetype !='image/gif'){
        		  $newim = imagecreatetruecolor($newwidth, $newheight);
        		}else{
        		  $newim = imagecreate($newwidth, $newheight);
        		}

        	// additional processing for png / gif transparencies (credit to Dirk Bohl)
        	if($imagetype == 'image/x-png' || $imagetype == 'image/png'){
        	
        		imagecolortransparent($newim, imagecolorallocatealpha($newim, 0, 0, 0, 127));
        		imagealphablending($newim, false);
        		imagesavealpha($newim, true);
            
            }elseif($imagetype == 'image/gif'){
            
        		$originaltransparentcolor = imagecolortransparent( $im );
        		if($originaltransparentcolor >= 0 && $originaltransparentcolor < imagecolorstotal( $im ))
        			{
        			$transparentcolor = imagecolorsforindex( $im, $originaltransparentcolor );
        			$newtransparentcolor = imagecolorallocate($newim,$transparentcolor['red'],$transparentcolor['green'],$transparentcolor['blue']);
        			imagefill( $newim, 0, 0, $newtransparentcolor );
        			imagecolortransparent( $newim, $newtransparentcolor );
        			}
        		}

           imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
           
           if($imagetype == 'image/pjpeg' || $imagetype == 'image/jpeg'){
                imagejpeg ($newim,$urlandname);
            }elseif($imagetype == 'image/x-png' || $imagetype == 'image/png'){
           	    imagepng ($newim,$urlandname);
           	}elseif($imagetype == 'image/gif'){
           	    imagegif ($newim,$urlandname);
           	}
        
           	imagedestroy ($newim);
        
        } else {
            
            if($imagetype == 'image/pjpeg' || $imagetype == 'image/jpeg'){
                imagejpeg ($im,$urlandname, 90);
            }elseif($imagetype == 'image/x-png' || $imagetype == 'image/png'){
                imagepng ($im,$urlandname);
            }elseif($imagetype == 'image/gif'){
                imagegif ($im,$urlandname);
            }
            
            
        }
	
	}
	
	
	
	
	/**
	 * convert_image function.
	 * 
	 * @access private
	 * @return image
	 */
	private function convert_image($imagetemp, $imagetype){
    	
    	if($imagetype == 'image/pjpeg' || $imagetype == 'image/jpeg'){
        
        	$im = imagecreatefromjpeg($imagetemp);
        
        }elseif($imagetype == 'image/x-png' || $imagetype == 'image/png'){
        
            $im = imagecreatefrompng($imagetemp);
            //imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
            imagealphablending($im, false);
            imagesavealpha($im, true);
        
        }elseif($imagetype == 'image/gif'){
            
            $im = imagecreatefromgif($imagetemp);
        }
        
        return $im;
    	
	}
	
	
	
	
	/**
	 * rotate_image function.
	 * 
	 * @access private
	 * @return void
	 */
	public function rotate_image(){
    	
    	$filename = $this->_doc_root.$this->_path;
    	//echo $filename;
    	$rotang = 90;
    	
    	// Get attrs
    	$attr = getimagesize($filename);

    	$imagetype = $attr['mime'];

        if($imagetype == 'image/pjpeg' || $imagetype == 'image/jpeg'){
            
            //echo 'Function check';
            if(!function_exists(imagerotate)){
                die('imagerotate does not exist');
            }
            
            $source = imagecreatefromjpeg($filename);
            $rotation = imagerotate($source, $rotang, -1);
            imagejpeg($rotation, $filename, 100);

            
        }elseif($imagetype == 'image/x-png' || $imagetype == 'image/png'){
           	
           	$source = imagecreatefrompng($filename);
            imagealphablending($source, false);
            imagesavealpha($source, true);
        
            $rotation = imagerotate($source, $rotang, imageColorAllocateAlpha($source, 0, 0, 0, 127));
            imagealphablending($rotation, false);
            imagesavealpha($rotation, true);
            imagepng($rotation, $filename, 100);
           	
        }elseif($imagetype == 'image/gif'){
           	/*
           	$source = imagecreatefromgif($filename);
            $rotation = imagerotate($source, $rotang, 1);
            imagegif($rotation, $filename);
            */
            $source = imagecreatefromgif($filename); 

            $w = imagesx($source); 
            $h = imagesy($source); 
            $bg = imagecolortransparent($source); 
            
            $timage = imagecreatetruecolor($w, $h); 
            imagefill($timage, 0, 0, $bg); 
            imagecopy($timage, $source, 0, 0, 0, 0, $w, $h); 
            
            $rotation = imagerotate($timage, 45, $bg); 
            imagecolortransparent($rotation, $bg);
            
            imagegif($rotation, $filename, 100);
           	
        }
        
        
        imagedestroy($source);
        imagedestroy($rotation);
    	
    	
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