<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImage;

class ImageController extends Controller
{
    public function postUpload(UploadImage $request){
        $file = $request->file('file');

        if($file->isValid()){

            $destination_path = public_path()."/images/";
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid('question',true).".".$extension;

            $file->move($destination_path,$filename);

            return response()->json([
                'success' => true,
                'src' => url("images/".$filename),
                'image' => "images/".$filename
            ]);
        }else{
            return response()->json('error',400);
        }
    }
}
