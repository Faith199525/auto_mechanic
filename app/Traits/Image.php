<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait Image {

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function renameImage()
    {

            $image = $this->request->file('image');
            $filenameWithExt = $image->getClientOriginalName();
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);

            $extension = $image->getClientOriginalExtension();
            $imageToStore = $filename.'_'.time().'.'.$extension;
            $image->storeAs('public/images',$imageToStore);

            return $imageToStore;

    }

     /**
     * Store the image.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeImage() 
    {
        if ($this->request->hasFile('image')){

            return $this->renameImage();
            
        }

            return 'no_image.jpg';
    }

    /**
     * Update the image.
     *
     * @return \Illuminate\Http\Response
     */

    public function updateImage($userProfile)
    {
        if ($this->request->hasFile('image')){

            $imageToStore = $this->renameImage();

            $this->deleteImage($userProfile);

         return $imageToStore;

      }
       return $imageToStore = 'no_image.jpg';
    }

    public function deleteImage($userProfile)
    {
        //Prevent the default image from being deleted
            if($userProfile->image !== 'no_image.jpg'){
               Storage::delete('public/images/'.$userProfile->image); 
            }
    }
}