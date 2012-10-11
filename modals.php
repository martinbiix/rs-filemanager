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
    
     
    
    <div class="folder-name-modal modal hide">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Rename Folder</h3>
      </div>
      <div class="modal-body">
        
        <div id="folder-rename-wrap">
        </div>
      
      </div>
      <div class="modal-footer">
        <button id="cancel-rename-folder" class="button">Close</button> 
      </div>
    </div>
    
    
    
    
    <div class="file-edit-modal modal hide">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Edit Image</h3> &nbsp;&nbsp; / &nbsp;&nbsp; <span id="crop-dim">Width: <span id="w">0</span>px / Height: <span id="h">0</span>px</span>
      </div>
      <div class="modal-body">
        
        <div id="file-to-edit">
        <p>Loading...</p>
        </div>
      
      </div>
      <div class="modal-footer">
        <button id="crop-image-button" class="button success">Start Crop</button> 
        <button id="save-crop-image-button" class="button primary">Save Crop</button>
        <button id="cancel-crop" class="button">Close</button> 
      </div>
    </div>
    
    
    
    
    <div class="image-options-modal modal hide">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Image Options</h3>
      </div>
      <div class="modal-body">
        
        <div id="image-options">
        <p>Loading...</p>
        </div>
      
      </div>
      <div class="modal-footer">
        <button id="cancel-image-options" class="button">Close</button> 
      </div>
    </div>