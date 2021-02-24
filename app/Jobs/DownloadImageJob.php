<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\UserImageDownloaded;
use App\Image;

class DownloadImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->image){
            // download image from url
            $path = $this->image->image_url;
            $filenames = explode('.', basename($path)); 
            $uploadname = $filenames[0].'_'.time() . '_.' . $filenames[1];
            \InterventionImage::make($path)->save(public_path('images/' . $uploadname));

            // update image model
            $ImageModel = Image::find($this->image->id);
            $ImageModel->image_src = '/images/' . $uploadname;
            $ImageModel->status = 'Completed';
            $ImageModel->save();

            event(new UserImageDownloaded(json_encode($ImageModel)));
            \Log::info('image job done');
        }
    }
}
