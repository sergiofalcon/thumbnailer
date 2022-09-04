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
        // Default values
        $this->numThumbnails = 0;
        $this->filePrefix = substr(str_shuffle("abcdef1234567890"),0,8);
        $this->targetDir = $_SERVER['DOCUMENT_ROOT'] . "/imageconverter/tests/results/";
        $this->quality = 85;
        
        // Check if file exists and get image format
        if(!file_exists($source)) {
            die("sergiofalcon/Thumbnailer - Fatal error: the specified file ($source) doesn't exists in that path.\r\n");
        } else {
            $this->source = realpath($source);
            $this->fileName = basename($this->source);
            $this->targetFormat = "jpg";
            $this->format = image_type_to_mime_type(exif_imagetype($this->source));
            
            if($this->format != "image/jpeg" && $this->format != "image/png") {
                die("sergiofalcon/Thumbnailer - Fatal error: image format of $this->source ($this->format) is not valid.\r\n");
            }

            $this->debugInfo = "";
            return($this);
        }
    }

    private function debugLog(string $string) {
        $this->debugInfo .= $string . "\r\n";
    }

    public function setFormat(string $format = "jpg")
    {
        if($format == "jpg" || $format == "webp") {
            $this->targetFormat = "jpg";
        } else {
            echo("Invalid image format");
            $this->debugInfo = "Format \"$format\" is not valid. Setted by default to \"$this->targetFormat\".";
            $this->format = "jpg";
        }
    }
    
    /*
    |
    | Setters
    |
    */ 

    public function setFilePrefix(string $filePrefix) {
        $this->filePrefix = $filePrefix;
        return($this);
    }

    public function setHeight(int $height) {
        $this->height = $height;
        return($this);
    }

    public function setQuality(int $quality) {
        $this->quality = $quality;
        return($this);
    }

    public function setRotation(int $rotationDegrees  = 0) {
        $this->rotationDegrees = $rotationDegrees;
        return($this);
    }

    public function setTargetDir(string $targetDir) {
        $this->targetDir = $targetDir;
        return($this);
    }

    public function setWidth(int $width) {
        $this->width = $width;
        return($this);
    }
    
    /*
    |
    | addThumbnail($width, $height)
    |
    | Adds a thumbnail size to the queue
    |
    */

    public function addThumbnail(int $width, int $height)
    {
        $tmpName = $this->targetDir . $this->filePrefix . "-" . $this->numThumbnails . "." . $this->targetFormat; 
        
        $this->numThumbnails++;
        $this->thumbnails["$this->numThumbnails"]['width'] = $width;
        $this->thumbnails["$this->numThumbnails"]['height'] = $height;
        $this->thumbnails["$this->numThumbnails"]['quality'] = $this->quality;
        $this->thumbnails["$this->numThumbnails"]['tmpName'] = $tmpName;

        return($this);
    }

    /*
    |
    | convert($source, $target, $width, $height) 
    |
    | Creates a thumbnail with the specified size
    |
    */

    private function convert($source, $target, int $width = 1024, int $height = 768)
    {

        if($this->targetFormat == "jpg") {
                        
            system("cp $source $target");

            $mogrify = "mogrify -verbose -resize $width"."x"."$height -quality $this->quality -format jpg $target &";
            self::debugLog("$mogrify");

            system($mogrify);
        }

        if($this->format == "webp") {
            $cwebp = "cwebp -mt -size $width $height -q $this->quality -lossless -o $target";
        }

    }

    public function save()
    {
        foreach($this->thumbnails AS $thumbnail) {
            self::convert($this->source, $thumbnail['tmpName'], $thumbnail['width'], $thumbnail['height'],  $thumbnail['quality']);
            
            if(file_exists($thumbnail['tmpName'])) {
                echo(system("file $thumbnail[tmpName]"));
                print_r(getimagesize($thumbnail['tmpName']));
            }
        }
    }
}