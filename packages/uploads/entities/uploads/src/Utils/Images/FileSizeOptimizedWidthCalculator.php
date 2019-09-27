<?php

namespace Packages\Uploads\Utils\Images;

use Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\FileSizeOptimizedWidthCalculator as PackageFileSizeOptimizedWidthCalculator;

/**
 * Class FileSizeOptimizedWidthCalculator.
 */
final class FileSizeOptimizedWidthCalculator extends PackageFileSizeOptimizedWidthCalculator
{
    /**
     * Стоппер для размеров адаптивных изображений.
     *
     * @param  int  $predictedFileSize
     * @param  int  $newWidth
     *
     * @return bool
     */
    protected function finishedCalculating(int $predictedFileSize, int $newWidth): bool
    {
        if ($newWidth < 100) {
            return true;
        }

        return false;
    }
}
