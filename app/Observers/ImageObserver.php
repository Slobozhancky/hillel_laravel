<?php

namespace App\Observers;

use App\Models\Image;
use App\Services\Contracts\FileStorageServiceContract;

class ImageObserver
{
    /**
     * Handle the Image "deleted" event.
     */
    public function deleted(Image $image): void
    {
        app(FileStorageServiceContract::class)->remove($image->path);
    }
}
