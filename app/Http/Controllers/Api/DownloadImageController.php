<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Image;
use App\Jobs\DownloadImageJob;
use Carbon\Carbon;

class DownloadImageController extends Controller
{
    /**
     * This function store user image information.
     *
     * @param  \Illuminate\Http\Request  $request 
     * @return mixed
     */
    public function store(Request $request)
    {
        $request->validate([
			'title'         => 'required',
			'image_url'     => 'required', 
		]);
        
        $image = Image::create([
            'user_id'       => $request->user()->id,
            'title'         => $request->title,
			'image_url'     => $request->image_url, 
            'status'        => 'Processing', 
        ]);  

        $job = (new DownloadImageJob($image))->delay(Carbon::now()->addSeconds(15)); //
        dispatch($job);
        return response()->json(['message'=>'Image job submited successfully', 'dd'=>Carbon::now()]);
    }

    /**
     * This function return user all images .
     *
     * @param  \Illuminate\Http\Request  $request 
     * @return mixed
     */
    public function getAllImages(Request $request)
    {
        $allImages = Image::where('user_id', $request->user()->id)->get();

        return response()->json(['data'=>$allImages]);
    }
}
