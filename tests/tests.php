<?php

namespace Sergiofalcon\Thumbnailer\Classes;

require("../src/Thumbnailer.php");

$file = "puppy.jpg";
$test1 = new Thumbnailer($file, $_SERVER['DOCUMENT_ROOT'] . "/imageconverter/tests/results/");
$test2 = new Thumbnailer($file, $_SERVER['DOCUMENT_ROOT'] . "/imageconverter/tests/results/");

$test1->setFilePrefix("very-cute-dog")
      ->setFormat("webp")
      ->setQuality(85)
      ->addThumbnail(1024,768)
      ->addThumbnail(750,500)
      ->addThumbnail(500,250)
      ->addThumbnail(250,125)
      ->addThumbnail(125,50)
      ->addThumbnail(50,25);

$fileLocation1 = $test1->save();
print_r($fileLocation1);

$test2->setFilePrefix("very-cute-dog")
      ->setFormat("jpg")
      ->setQuality(85)
      ->addThumbnail(1024,768)
      ->addThumbnail(750,500)
      ->addThumbnail(500,250)
      ->addThumbnail(250,125)
      ->addThumbnail(125,50)
      ->addThumbnail(50,25);

$fileLocation2 = $test2->save();
print_r($fileLocation2);
