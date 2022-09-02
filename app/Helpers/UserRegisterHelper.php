<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Mail;

class UserRegisterHelper
{
    public function register($data)
    {
        $user = User::create($data)
        if($user){
            $user->profile_pic = $filename;
            $user->save();
        }
        return $user->id;
    }

    public function uploadProfilePic($data)
    {
        $uuid = Uuid::uuid1()->toString();
        $file = $data->file(PROFILE_PIC);
        $mime = $file->getClientMimeType();
        $extension = explode('/', $mime)[1];
        $filename = $uuid . '.' . $extension;
        $path = 'images/user_profile_images';
        $pathPrefix = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $path . '/' . $filename;
        if (!Storage::has($path)) {
            File::makeDirectory($pathPrefix . $path, 0777, true, true);
        }
        $img = Image::make($file);
        $img->save($storagePath);
        $this->saveUserProfileThumbnail($filename, $img);
        return $filename;

    }

    public function saveUserProfileThumbnail($filename, $img) {
        $path = 'images/user_profile_thumbnail';
        $img->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $pathPrefix = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        if (!Storage::has($path)) {
            File::makeDirectory($pathPrefix . $path, 0777, true, true);
        }
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $path . '/' . $filename;
        $img->save($storagePath);
        return true;
    }
}
