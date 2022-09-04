<?php

namespace io42;

/*
| 
|--------------------------------------------------------------------------------------------
| Thumbnailer by Sergio Falcón (https://github.com/sergiofalcon/thumbnailer)
|--------------------------------------------------------------------------------------------
|
*/

class Thumbnailer {

    public function __construct(public string $source, string $targetDir)
    {

        // Default values
        self::setTargetDir($targetDir);
        $this->numThumbnails = 1;
        $this->filePrefix = substr(str_shuffle("abcdef1234567890"),0,8);
        $this->quality = 85;
        $this->targetFormat = "jpg";
        $this->debugInfo = "";

        // Check if file exists and get image format
        if(!file_exists($source)) {
            echo("Thumbnailer - Fatal error: the specified file ($source) doesn't exists in that path.\r\n");
        } else {

            $this->source = realpath($source);
            $this->fileName = basename($this->source);
            $this->format = image_type_to_mime_type(exif_imagetype($this->source));
            
            if($this->format != "image/jpeg" && $this->format != "image/png") {
                echo("Thumbnailer - Fatal error: image format of $this->source ($this->format) is not valid.\r\n");
            }

            return($this);
        }
    }

    private function debugLog(string $string) {
        $this->debugInfo .= "\r\n---------------------------------- \r\n";
        $this->debugInfo .= "\r\n" . $string . "\r\n";
        $this->debugInfo .= "\r\n---------------------------------- \r\n";
    }

    /*
    |
    | Setters
    |
    */ 

    public function setFormat(string $format = "jpg")
    {
        if($format == "jpg" || $format == "webp") {
            $this->targetFormat = $format;
        } else {
            echo("Invalid image format");
            self::debugLog("Thumbnailer: Format \"$format\" is not valid. Setted by default to \"$this->targetFormat\"");
            $this->format = "jpg";
        }

        return($this);
    }

    public function setFilePrefix(string $filePrefix) {
        $this->filePrefix = $filePrefix;
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
        if(is_dir($targetDir)) {
            $this->targetDir = $targetDir;
            return($this);
        } else {
            echo("Thumbnailer: $targetDir is not a valid directory");
        }
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

            $mogrify = "mogrify -verbose -resize $width"."x"."$height -quality $this->quality -format jpg $target 2>&1";
            self::debugLog("$mogrify");

            self::debugLog(`$mogrify`);
        }

        if($this->targetFormat == "webp") {
            
            if($height == $width) {
                $height = 0;
            }
            if($width > $height) { 
                $height = 0;
            }
            if($height > $width) {
                $width = 0;
            }

            $cwebp = "cwebp -mt -resize $width $height -q $this->quality -lossless $source -o $target 2>&1";
            self::debugLog("$cwebp");
            self::debugLog(`$cwebp`);
        }

    }

    /*
    |
    | save()
    |
    | Proccess the thumbnail queue and saves files on the target directory with the specified file prefix
    |
    */    

    public function save()
    {
        foreach($this->thumbnails AS $thumbnail) {
            self::convert($this->source, $thumbnail['tmpName'], $thumbnail['width'], $thumbnail['height'],  $thumbnail['quality']);
            
            if(file_exists($thumbnail['tmpName'])) {
                
            }
        }
        $output = ((array) $this);
        $output = $output['thumbnails'];
        return($output);
    }
}