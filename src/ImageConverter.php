<?php

namespace io42\ImageConverter;

/*
| 
|--------------------------------------------------------------------------------------------
| imageConvert Class by io42.com
|--------------------------------------------------------------------------------------------
|
*/

class ImageConverter {

    public function __construct(public string $source)
    {
        $this->numThumbnail = 0;
        $this->debugInfo = null;

        if(!file_exists($source)) {
            return("sergiofalcon/imageconvert - Fatal error: the specified file ($source) doesn't exists in that path.\r\n");
        } else {
            $this->source = realpath($source);
            $this->target = $this->source . "-copy";
            $this->targetFormat = "jpg";
            copy($this->source, $this->target);
            $this->format = image_type_to_mime_type(exif_imagetype($this->source));
            if($this->format != "image/jpeg" || $this->format != "image/png") {
                die("sergiofalcon/imageconvert - Fatal error: image format of ($this->format) is not valid.");
            }
            return($this);
        }
    }

    public function withFormat(string $format = "jpg")
    {
        if($format == "jpg" || $format == "webp") {
            $this->targetFormat = "jpg";
        } else {
            echo("Invalid image format");
            $this->debugInfo = "Format \"$format\" es not valid. Setted by default to \"$this->targetFormat\".";
            $this->format = "jpg";
        }
    }
    
    public function mantainAspectRatio(bool $mantainAspectRatio = true)
    {
        $this->mantainAspectRatio = $mantainAspectRatio;
        return($this);
    }

    public function withQuality(int $quality) {
        $this->quality = $quality;
        return($this);
    }

    public function withWidth(int $width = 1920) {
        $this->width = $width;
        return($this);
    }

    public function withHeight(int $height = 1080) {
        $this->height = $height;
        return($this);
    }

    public function withRotation(int $rotationDegrees  = 0) {
        $this->rotationDegrees = $rotationDegrees;
        return($this);
    }
    
    public function addThumbnail(int $width, int $height, bool $mantainAspectRatio = true, int $quality = 85)
    {
        $this->numThumbnail++;
        $this->thumbnails[$this->numThumbnail]['width'] = $width;
        $this->thumbnails[$this->numThumbnail]['height'] = $height;
        $this->thumbnails[$this->numThumbnail]['mantainAspectRatio'] = $mantainAspectRatio;
        $this->thumbnails[$this->numThumbnail]['quality'] = $quality;

        return($this);
    }

    public function compress($calidad)
    {

    }

    public function convert()
    {
        //exec("mogrify ");
    }
}