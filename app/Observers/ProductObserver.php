<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\Contracts\FileStorageServiceContract;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    public function deleted(Product $product): void
    {
        if ($product->images) {
            $product->images->each->delete();
        }

        app(FileStorageServiceContract::class)->remove($product->thumbnail);
        Storage::deleteDirectory("public/$product->slug");
    }
}
