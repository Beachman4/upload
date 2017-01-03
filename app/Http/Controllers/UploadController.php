<?php

namespace App\Http\Controllers;

use App\Upload;
use Illuminate\Http\Request;
use App\User;
use Response;
use Auth;

class UploadController extends Controller
{

    private $user;

    private $uploader;
    /**
     * UploadController constructor.
     */
    public function __construct(User $user, Upload $upload)
    {
        $this->user = $user;
        $this->uploader = $upload;
    }

    public function test()
    {
        return view('test');
    }
    public function postUpload(Request $request)
    {
        $apiKey = $request->input('apiKey');
        $user = $this->user->authorize($apiKey);
        $file = $request->file('file');
        $file = $this->uploader->uploadFile($file, $user);
        echo $_SERVER['HTTP_HOST'] . '/files/' . $file->name;

    }

    public function getFile($file)
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $file;
        if (file_exists($path)) {
            $file = $this->uploader->where('name', $file)->first();
            $type = 0;
            $mime = $file->file_type;
            if (str_contains($mime, 'image')) {
                $type = 0;
            } elseif(str_contains($mime, 'video')) {
                $type = 1;
            } elseif(str_contains($mime, 'audio')) {
                $type = 2;
            } else {
                return Response::download($path);
            }
            return view('file', [
                'file' => $file->name,
                'type' => $type
            ]);
        }
        abort(404);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            return Auth::User()->apiKey;
        }
    }
}
