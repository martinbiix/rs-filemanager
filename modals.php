    <!-- NEW FOLDER -->
    <div class="new-folder-modal modal hide">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>New Folder</h3>
      </div>
      <div class="modal-body">
        
        <p>
        Enter your new folder name. Must be more than 2 characters long. Please no special characters, they will be removed.
        </p>
            
        Folder Name: <input type="text" name="" id="new-folder-name"> 
      
      </div>
      <div class="modal-footer">
        <button id="add-new-folder-button" class="button primary">Add Folder</button> 
        <button id="cancel-new-folder" class="button">Close</button> 
      </div>
    </div>   
    
     
    <!-- RENAME FOLDER -->
    <div class="folder-name-modal modal hide">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Rename Folder</h3>
      </div>
      <div class="modal-body">
        
        <div class="notify warning">
            <div class="notify-inner">
                <p><span class="bold">Warning.</span> When renaming folders, any links on your website that use files within this folder will no longer work.</p>    
            </div>
        </div>
        
        <p>
        Enter your new folder name. Must be more than 2 characters long. Please no special characters, they will be removed.
        </p>
        
        <div id="folder-rename-wrap">
            Rename Folder: <input type="text" id="folder-name">
        </div>
        <input type="hidden" id="old-path">
      
      </div>
      <div class="modal-footer">
        <button id="rename-folder-button" class="button primary">Rename Folder</button>
        <button id="cancel-rename-folder" class="button">Close</button> 
      </div>
    </div>
    
    
    
    <!-- EDIT IMAGE -->
    <div class="file-edit-modal modal hide">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Edit Image</h3> &nbsp;&nbsp; / &nbsp;&nbsp; <span id="crop-dim">Crop Width: <span id="w">0</span>px / Height: <span id="h">0</span>px</span>
        <span id="edit-image-message"></span>
      </div>
      <div class="modal-body">
        
        <div id="file-options">
            <div id="image-options">
            <p>Loading...</p>
            </div>
            
            <div id="file-to-edit">
            <p>Loading...</p>
            </div>
        </div>
      
      </div>
      <div class="modal-footer">
        <div id="edit-image-button-options">
        <a id="rotate-image" class="button float-left"><b></b> Rotate</a>
        <a id="crop-image-button" class="button float-left"><b></b> Start Crop</a>
        <a id="cancel-crop-image-button" class="button float-left hide"><b></b> Cancel Crop</a> 
        <a id="save-crop-image-button" class="button float-left hide"><b></b> Save Crop</a>
        </div>
        
        <button id="select-image-button" class="button primary2">Select Image</button>
        <button id="cancel-crop" class="button">Close</button> 
      </div>
    </div>
    
    
    <!-- UPLOADER -->
    <div class="uploader-modal modal hide">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Upload Files</h3> &nbsp;&nbsp; / &nbsp;&nbsp; <span id="uploading-location"></span>
      </div>
      <div class="modal-body">
        
        <div class="notify error upload-error hide">
            <div class="notify-inner">   
            </div>
        </div>
        
        <div id="filelist-wrap"><strong>File Queue:</strong> <div id="filelist"></div></div>      
        
        <div id="upload-progress-bg">
            <div id="upload-progress-wrap">
                <div id="upload-progress"></div>
            </div>
        </div>
        
        <button type="button" id="pickfile" class="button success">Choose Files</button>
        <div class="upload-or">or</div>
        <div id="filedrop">Drag files here<span id="html5-fileupload"></span></div>
        <!-- / #filedrop -->
                               
        <div id="allowed-files">Allowed: <?php echo implode(', ',$img_types) ?>, <?php echo implode(', ',$doc_types) ?>
        <br>(Filesize <?php echo MAX_UPLOAD_SIZE; ?>MB max)</div>
      
        <div id="custom-sizes">
            Custom image size: <input type="text" id="custom-width" maxlength="4"> px Width X <input type="text" id="custom-height" maxlength="4"> px Height
        </div>
      
      </div>
      <div class="modal-footer">
         
        <button type="button" id="uploadfiles" class="button primary" style="display: none">Upload File</button>
        <button type="button" id="cancelfile" class="button">Close</button> 
      </div>
    </div>