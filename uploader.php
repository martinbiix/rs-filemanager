        
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
                               
        <div id="allowed-files">Allowed: jpg, png, gif, doc, txt, rtf, xls, ppt, pages, numbers, pdf, exe, dmg, zip, mp3, mp4, ogg, ogv, flv, mpg<br>(Filesize <?php echo MAX_UPLOAD_SIZE; ?>MB max.)</div>
      
        <div id="custom-sizes">
            Custom image size: <input type="text" id="custom-width" maxlength="4"> px Width X <input type="text" id="custom-height" maxlength="4"> px Height
        </div>
      
      </div>
      <div class="modal-footer">
         
        <button type="button" id="uploadfiles" class="button primary" style="display: none">Upload File</button>
        <button type="button" id="cancelfile" class="button">Close</button> 
      </div>
    </div>