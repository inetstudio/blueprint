<?php

namespace Packages\Uploads\Utils\ImageGenerators;

use Illuminate\Support\Collection;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Image as PackageImage;

class Image extends PackageImage
{
    public function supportedExtensions(): Collection
    {
        return collect(['png', 'jpg', 'jpeg', 'gif', 'tif', 'tiff']);
    }

    public function supportedMimeTypes(): Collection
    {
        return collect(['image/jpeg', 'image/gif', 'image/png', 'image/tiff']);
    }
}
