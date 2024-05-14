<?php
namespace Modules\Media\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Media\Helpers\FileHelper;
use Modules\Media\Models\MediaFile;
use Modules\Media\Models\MediaFolder;
use Modules\Media\Resources\MediaResource;
use Modules\Media\Resources\FolderResource;
use Modules\Media\Traits\HasUpload;

class MediaController extends Controller
{

    use HasUpload;

    public function index(Response $request){

        $this->setActiveMenu(route('media.admin.index'));
        $data = [
            'page_title'=>__("Media Management"),
            'breadcrumbs'        => [
                [
                    'name' => __('Media Management'),
                    'url'  => route('media.admin.index')
                ],
            ]
        ];
        return view('Media::admin.index', $data);
    }

    public function sendError($message, $data = [])
    {
        $data['uploaded'] = 0;
        $data['error'] = [
            "message"=>$message
        ];

        return parent::sendError($message,$data);
    }

    public function sendSuccess($data = [], $message = '')
    {
        $data['uploaded'] = 1;

        if(!empty($data['data']->file_name))
        {
            $data['fileName'] = $data['data']->file_name;
            $data['url'] = FileHelper::url($data['data']->id,'full');
        }
        return parent::sendSuccess($data, $message); // TODO: Change the autogenerated stub
    }

    public function store(Request $request)
    {

        if(!\auth()->user()){
            return $this->sendError(__("Please log in"));
        }

        $ckEditor = $request->query('ckeditor');

        if (!$this->hasPermissionMedia()) {
            return $this->sendError('There is no permission upload');
        }
        $fileName = 'file';
        if($ckEditor) $fileName = 'upload';

        try{
            $file_type = $request->input('type','image');

            $fileObj = $this->uploadFile($request,$fileName,$file_type,$request->input('folder_id'));

            return $this->sendSuccess(['data' => $fileObj]);

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
    public function getLists(Request $request)
    {
        if (!$this->hasPermissionMedia()) {
            return $this->sendError('There is no permission upload');
        }
    
        $file_type = $request->input('file_type', 'image');
        $s = $request->input('s');
        $folder_id = $request->input('folder_id', 0);
    
        // Initialize the file query
        $filesQuery = MediaFile::query();
    
        // if (!Auth::user()->hasPermission("media_manage_others")) {
        //     $filesQuery->where('author_id', Auth::id());
        // }
    
        $uploadConfigs = config('bc.media.groups');
        if (!isset($uploadConfigs[$file_type])) {
            return $this->sendError('File type not found');
        }
    
        $config = $uploadConfigs[$file_type] ?? $uploadConfigs['default'];
        $filesQuery->whereIn('file_extension', $config['ext']);
    
        if ($folder_id) {
            $filesQuery->where('folder_id', $folder_id);
        } else {
            $filesQuery->where('folder_id', 0);
        }
    
        if ($s) {
            $filesQuery->where('file_name', 'like', '%' . $s . '%');
        }
    
        // Execute the file query
        $files = $filesQuery->orderBy('file_name', 'asc')->paginate(1000);
    
        // Initialize the folder query
        $foldersQuery = MediaFolder::query();
        // if (!Auth::user()->hasPermission("media_manage_others")) {
        //     $foldersQuery->where('author_id', Auth::id());
        // }
    
        if ($folder_id) {
            $foldersQuery->where('parent_id', $folder_id);
        } else {
            $foldersQuery->where('parent_id', 0);
        }
    
        if ($s) {
            $foldersQuery->where('name', 'like', '%' . $s . '%');
        }
    
        // Execute the folder query
        $folders = $foldersQuery->orderBy('name', 'asc')->paginate(1000);
    
        // Prepare the response
        $filesRes = $files->map(function ($file) {
            return new MediaResource($file);
        });
    
        $foldersRes = $folders->map(function ($folder) {
            return new FolderResource($folder);  // Assume FolderResource exists
        });
    // dd($filesRes,$foldersRes, $files,$folders);
        return $this->sendSuccess([
            'files' => $filesRes,
            'folders' => $foldersRes,
            'totalFiles' => $files->total(),
            'totalFolders' => $folders->total(),
            'totalFilePages' => $files->lastPage(),
            'totalFolderPages' => $folders->lastPage(),
            'accept' => $this->getMimeFromType($file_type)
        ]);
    }
    
    protected function getMimeFromType($file_type){
        switch ($file_type){
            case 'video':
                return "video/*";
                break;
            case 'cvs':
                return implode(',',[
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/vnd.ms-powerpoint',
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ]);
                break;
            case 'scorm':
                return implode(',',[
                    'application/x-gzip',
                    'application/zip',
                    'application/x-rar-compressed'
                ]);
                break;
            default:
                return "";
                break;
        }
    }

    /**
     * Check Permission Media
     *
     * @return bool
     */
    private function hasPermissionMedia()
    {
        if(Auth::id()){
            return true;
        }
        if (Auth::user()->hasPermission("media_upload")) {
            return true;
        }
        if (Auth::user()->hasPermission("media_manage_others")) {
            return true;
        }
        return false;
    }

    public function ckeditorBrowser(){
        return view('Media::ckeditor');
    }

    public function removeFiles(Request $request){
        if(is_demo_mode()){
            return $this->sendError(__("Can not remove!"));
        }
        $file_ids = $request->input('file_ids');
        $driver = config('filesystems.default','uploads');
        if(empty($file_ids)){
            return $this->sendError(__("Please select file"));
        }
        if (!$this->hasPermissionMedia()) {
            return $this->sendError(__("You don't have permission delete the file!"));
        }
        $model = MediaFile::query()->whereIn("id",$file_ids);
        if (!Auth::user()->hasPermission("media_manage_others")) {
            $model->where('author_id', Auth::id());
        }
        $files = $model->get();
        $storage = Storage::disk($driver);
        if(!empty($files->count())){
            foreach ($files as $file){
                if($storage->exists($file->file_path)){
                    $storage->delete($file->file_path);
                }
                $size_mores = FileHelper::$defaultSize;
                if(!empty($size_mores)){
                    foreach ($size_mores as $size){
                        $file_size = substr($file->file_path, 0, strrpos($file->file_path, '.')) . '-' . $size[0] . '.' . $file->file_extension;
                        if($storage->exists($file_size)){
                            $storage->delete($file_size);
                        }
                    }
                }
                $file->forceDelete();
            }
            return $this->sendSuccess([],__("Delete the file success!"));
        }
        return $this->sendError(__("File not found!"));
    }

    public function editImage(Request $request){
        $validate = [
            'image'     => 'required',
            'image_id'  => 'required',
        ];
        $request->validate($validate);

        if (!Auth::user()->hasPermission("media_upload")) {
            $result = [
                'message' => __('403'),
                'status'=>0
            ];
            return $result;
        }

        $image_id = $request->input('image_id');
        $image_data = $request->input('image');

        $file = MediaFile::find($image_id);
        if(!$file){
            return $this->sendError("File not found");
        }

        try{

            $res = $file->editImage($image_data);
            return $this->sendSuccess($res);

        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
