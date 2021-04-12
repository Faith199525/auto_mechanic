<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImageFile;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    public function viewImagesById (Request $request, $Id)
    {
        $images= ImageFile::find($Id);

        if(\Storage::exists($images->path)) {
            return response()->file(storage_path() . '/app/' . $images->path);
        } else {
            return 'false';
        }
    }

    public function deleteImageById(Request $request, $Id)
    {
        $images= ImageFile::find($Id);

        if (\Storage::exists($images->path)) {
            \Storage::delete($images->path);

        $images->delete();

        return response()->json([
            'message'=>'Image successfully deleted'
        ], 200);

        }else{
            return response()->json([
            'message'=> 'Image does not exist'
        ], 400);

        }
       
    }

}
