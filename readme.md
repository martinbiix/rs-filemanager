# Really Simple File Manager

Used with online content editors such as CKEditor

<a href="http://tstdv.us/filemanager/">Download production version<a>

### Features

- File uploading
- Image cropping
- Restict crop size
- Image rotation
- Image resizing
- File view options
- Create/edit folders

### Instructions

Upload files to web server and edit the config.php file.

### CKEditor Integration

    CKEDITOR.replace( 'editor1',
    {
    	filebrowserBrowseUrl : '/location/of/rs_file_manager/?editor=ckeditor',
    	filebrowserImageBrowseUrl : '/location/of/rs_file_manager/?editor=ckeditor&file_type=images',
    	filebrowserWindowWidth : '940',
     	filebrowserWindowHeight : '640'
    });

The width and height suggested values are highly recommended.

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
jCrop - Image cropping
BlockUI - loading overlay
Twitter Bootstrap

Without these I could not have made this so quickly.

License
----
Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.