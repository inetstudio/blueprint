<?php

namespace Packages\Uploads\Utils\Images;

use Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\FileSizeOptimizedWidthCalculator as PackageFileSizeOptimizedWidthCalculator;

final class FileSizeOptimizedWidthCalculator extends PackageFileSizeOptimizedWidthCalculator
{
    protected function finishedCalculating(int $predictedFileSize, int $newWidth): bool
    {
        if ($newWidth < 100) {
            return true;
        }

        return false;
    }
}
