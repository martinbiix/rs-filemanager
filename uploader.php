    <a href="#" id="upload-modal-button"><i class="icon-upload"></i> Upload</a>
        
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
      
        <div id="filedrop">Drop files here</div>
        <!-- / #filedrop -->
                               
        <div id="allowed-files">Allowed: jpg, png, gif, doc, txt, rtf, xls, ppt, pages, numbers, pdf, exe, dmg, zip<br>(Filesize 10MB max.)</div>
      
        <div id="custom-sizes">
            Custom size: <input type="text" id="custom-width" maxlength="4"> px Width X <input type="text" id="custom-height" maxlength="4"> px Height
        </div>
      
      </div>
      <div class="modal-footer">
        <button type="button" id="pickfile" class="button success">Choose File(s)</button> 
        <button type="button" id="uploadfiles" class="button primary" style="display: none">Upload File(s)</button>
        <button type="button" id="cancelfile" class="button" style="display: none">Cancel</button> 
      </div>
    </div>