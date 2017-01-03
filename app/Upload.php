<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Storage;
use Illuminate\Http\File;

class Upload extends Model
{
    protected $fillable = ['user_id', 'name', 'file_type'];


    public function uploadFile(UploadedFile $file, $user)
    {
        $tempExtension = $file->getExtension();
        if ($tempExtension == "") {
            $explode = explode(".", $file->getClientOriginalName());
            $extension = $explode[count($explode) - 1];
            $name = $this->generateName() . '.' . $extension;
            $storage = Storage::putFileAs('files', new File($file->getRealPath()), $name);
            if ($storage) {
                $upload = $this->create([
                    'user_id' => $user->id,
                    'name' => $name,
                    'file_type' => $file->getClientMimeType()
                ]);
                return $upload;
            }
        } else {
            $name = $this->generateName() . '.' . $tempExtension;
            $storage = Storage::putFileAs('files', new File($file->getRealPath()), $name);
            if ($storage) {
                $upload = $this->create([
                    'user_id' => $user->id,
                    'name' => $name,
                    'file_type' => $file->getClientMimeType()
                ]);
                return $upload;
            }
        }
        abort(500);
    }
    private function generateName()
    {
        $name = uniqid();
        if ($this->checkName($name)) {
            $name = $this->generateName();
        }
        return $name;
    }

    private function checkName($name)
    {
        $uploads = $this->get();
        foreach($uploads as $upload) {
            if (str_contains($upload->name, $name)) {
                return true;
            }
        }
        return false;
    }
}
