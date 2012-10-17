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
        <button id="rotate-image" class="button"><i class="icon-refresh"></i> Rotate</button>
        <button id="crop-image-button" class="button success">Start Crop</button> 
        <button id="save-crop-image-button" class="button primary">Save Crop</button>
        <button id="cancel-crop" class="button">Close</button> 
      </div>
    </div>