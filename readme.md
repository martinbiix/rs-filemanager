# Really Simple File Manager

Used with online content editors such as CKEditor

### Features

- File uploading
- Image cropping
- Restict crop size
- Image rotation
- Image resizing
- File view options

### Instructions

Upload files to web server and edit the config.php file.

### CKEditor Integration

    CKEDITOR.replace( 'editor1',
    {
    	filebrowserBrowseUrl : '/location/of/rs_file_manager/',
    	filebrowserImageBrowseUrl : '/location/of/rs_file_manager/?file_type=images',
    	filebrowserWindowWidth : '940',
     	filebrowserWindowHeight : '640'
    });

The width and height suggested values are higly recommended.

## Optional inital loading parameters:

    /location/of/rs_file_manager/?file_type=images&crop_minWidth=80&crop_minHeight=80

#### Editor Type

editor (ckeditor)

#### Cropping restrictions

crop_minWidth (pixel value)

crop_minHeight (pixel value)

crop_maxWidth (pixel value)

crop_maxHeight (pixel value)

#### File type view

file_type (files | images)

#### List view

list_view (list | folder)

#### Order by

order_by (type | name | filesize)

Credits
----
PLUpload - For file upload capability.