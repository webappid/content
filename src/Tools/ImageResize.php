<?php

namespace WebAppId\Content\Tools;

use Gumlet\ImageResize as BaseImageResize;
use Gumlet\ImageResizeException;

class ImageResize extends BaseImageResize
{
    /**
     * Outputs image to browser
     * @param string $image_type
     * @param integer $quality
     * @throws ImageResizeException
     */
    public function output($image_type = null, $quality = null)
    {
        $image_type = $image_type ?: $this->source_type;
        
        $this->save(null, $image_type, $quality);
    }
}