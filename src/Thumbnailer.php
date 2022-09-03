<?php

namespace io42;

/*
| 
|--------------------------------------------------------------------------------------------
| imageConvert Class by io42.com
|--------------------------------------------------------------------------------------------
|
*/

class Thumbnailer {

    public function __construct(public string $source)
    {
        $this->numThumbnails = 0;
        $this->debugInfo = "";
        $this->tmpDir = sys_get_temp_dir() . "/";

        if(!file_exists($source)) {
            die("sergiofalcon/imageconvert - Fatal error: the specified file ($source) doesn't exists in that path.\r\n");
        } else {
            $this->source = realpath($source);
            $this->fileName = basename($this->source);
            $this->target = $this->source . "-copy";
            $this->targetFormat = "jpg";
            copy($this->source, $this->target);
            $this->format = image_type_to_mime_type(exif_imagetype($this->source));
            if($this->format != "image/jpeg" && $this->format != "image/png") {
                die("sergiofalcon/imageconvert - Fatal error: image format of $this->source ($this->format) is not valid.\r\n");
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
            $this->debugInfo = "Format \"$format\" is not valid. Setted by default to \"$this->targetFormat\".";
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
        $randomString = substr(str_shuffle("abcd1234"), 0, 8);
        $tmpName = $this->tmpDir . "tmp-" . $randomString . "-" . $this->numThumbnails . "-" . $width . "x" . $height; 
        
        $this->numThumbnails = $this->numThumbnails + 1;
        $this->thumbnails["$this->numThumbnails"]['width'] = $width;
        $this->thumbnails["$this->numThumbnails"]['height'] = $height;
        $this->thumbnails["$this->numThumbnails"]['mantainAspectRatio'] = $mantainAspectRatio;
        $this->thumbnails["$this->numThumbnails"]['quality'] = $quality;
        $this->thumbnails["$this->numThumbnails"]['tmpName'] = $tmpName;

        return($this);
    }

    public function convert($source, $target, int $width = 1024, int $height = 768, int $quality = 85)
    {

        if($this->format == "image/jpeg") {
            $mogrify = "mogrify -resize $width"."x"."$height -quality $quality -format jpg $target\r\n";
            $this->debugInfo .= "$mogrify"; 
            copy($source, $target . $this->targetFormat);
            exec($mogrify);
        }

        if($this->format == "image/webp") {
            // Coming soon
        }

    }

    public function save()
    {
        foreach($this->thumbnails AS $thumbnail) {
            self::convert($this->source, $thumbnail['tmpName'], $thumbnail['width'], $thumbnail['height'],  $thumbnail['quality']);
        }
    }
}