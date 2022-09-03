<?php

namespace io42;

require("../src/ImageConverter.php");

$file = "puppy.jpg";
$test = new Thumbnailer($file);

/** 
 * 
 * Ejemplo: Creates 4 thumbnails with different dimensions and returns an array with their temporary names
 * 
 * $test->addThumbnail(1024,768)
 *       ->addThumbnail(800,600)
 *       ->addThumbnail(400,100)
 *       ->addThumbnail(100,50)
 *       ->save();
 * 
*/

$test->addThumbnail(1024,768)
      ->addThumbnail(800,600)
      ->addThumbnail(400,100)
      ->addThumbnail(100,50)
      ->save();

      print_r($test);



#$imagen->addThumbnail(1024,768)->addThumbnail(800,600)->addThumbnail(400,100)->addThumbnail(100,50);

/**
 * 
 * Ejemplo: Redimensiona una imagen a full HD a calidad 85 sin rotación
 * 
 * Como el formato no se ha especificado, se establece JPG por defecto
 * 
 *
 * $imagen->withWidth(1920)
 *       ->withHeight(1080)
 *       ->withQuality(85)
 *       ->withRotation(0);
 * 
 */

#new imagen($origen,$destino);
#$imagen->withWidth(1920)->withHeight(1080)->withQuality(85)->withRotation(0)->save();

#print_r($prueba->thumbnails);
#print_r($prueba->so#urce);
#echo"\r\n";