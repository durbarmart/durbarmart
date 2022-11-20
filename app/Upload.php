<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    public static function image($request, $filename, $path)
    {
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }
        $image = $request->$filename;
        $imageName = time().'.' .$image->getClientOriginalExtension();
        return ["image"=> $image, "imageName"=>$imageName];
    }
}
