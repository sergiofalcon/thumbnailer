# PHP Thumbnailer 🎆

## A simplycity-first, speed focused, Imagemagick &amp; WebP powered PHP class designed to simply manipulate images and thumbnails on PHP CRUDs, specially crafted to be used on Laravel projects as an alternative to intervention/image and GD Library manipulation.

The aim of this class is to be able to make multiple thumbnails of images quickly and easily, using ImageMagick and command line CWebP. 

I preferred to use the command line versions rather than the PHP libraries themselves because I was looking for the best possible performance, and all the tests I did with the libraries didn't convince me, especially in the case of uploading dozens of images and having to make 4 or 5 thumbnails of each one. Therefore, it is important to keep in mind that we will need to have the "imagemagick" and "webp" packages installed and that their binaries can be executed by the system user of the web server.

I created this mainly because I was tired of having to use external libraries or expensive SaSS to generate images that already had good compression ratios and passed through Lighthouse without problem.

# Requirements

- ImageMagick v6 (not tested on v7 yet) https://imagemagick.org/script/download.php.
- WebP (https://developers.google.com/speed/webp/download).
- Ability of calling system() on your PHP build.

# Installation

- ```composer require sergiofalcon/thumbnailer```

# Use 

## Step 1: Instance a new thumbnailer object with 2 parameters

- The source file (can be a JPG or PNG, everything else will fail)
- The target directory of your local filesystem (must exists before call save() method, so if you want to organize your thumbnails in folders, you must create it before)

```
$thumbnails = new Thumbnailer("puppy.jpg", $_SERVER[DOCUMENT_ROOT]."/storage/);
```

_Important note: this class is designed to be used as a new object and not by static function calls._

## Step 2 (Optional): Customize your thumbnails

### Specify a file prefix

- _Optional_: Add your file prefix to generate your files with an speficied name pattern. The order of thumbnail generation is important because the class with append the number of iteration on as a file suffix.

- _By default_, if you don't specify a prefix, filenames will match a pattern of a 8 random hexadecimal string like "abcd1234-1.jpg".

- ```$thumbnails->setFilePrefix("my-prefix"); // Will generate files named like "my-prefix-01.jpg```

### Specify an image format

- _Optional_: Specificy in which format you want to thumbnails. **Only "jpg" and "webp" are allowed**.

- _By default_, if you don't specify a format, the class will create the thumbnails as JPG files.

- ```$thumbnails->setFormat("webp"); // Will set format to WebP```

### Specify thumbnail image quality

- _Optional_: Specificy the JPEG or WebP quality of your thumbnails, from 1 to 100. 

- _By default_, if you don't specify a quality, 85 will be used. WebP compression is always set to lossless.

- ```$thumbnails->setQuality(70); // Will set quality to 70```

## Step 3: Add some thumbnail sizes to generate

You can add as many image variations as you like, bearing in mind that the more you add, the longer the process takes and the greater the risk of timeout. If you are going to generate more than 20 thumbnails in each process, I advise you to raise the value of _max_execution_time_ in your _php.ini_.

Thumbnailer will _always_ create the thumbnails with the same aspect ratio of the original image, taking as a reference value the longest side of the image.

```
$thumbnails->addThumbnail(1024,768) // Suffix will be "-0"
           ->addThumbnail(800,600)  // Suffix will be "-1"
           ->addThumbnail(500,50)   // Suffix will be "-2"
           ->addThumbnail(250,50)   // Suffix will be "-3"
           ->addThumbnail(100,50)   // Suffix will be "-4"
           ->addThumbnail(50,25)    // Suffix will be "-5"
```

## Step 4: Save your thumbnails

The save() method will generate the thumbnails and return an array with the file location.

```
$locationInfo = $thumbnails->save();
```

Will return an array with this structure
```
[0] => Array
    (
        [width] => 1024
        [height] => 768
        [quality] => 85
        [tmpName] => /var/www/vhosts/sergiofalcon.com/httpdocs/imageconverter/tests/results/very-cute-dog-1.webp
    )
```

Once this is done, you can take the tmpName key from the array if you need to move the files to another storage type, folder or similar.

## Thanks and credits

- Fernanda Nuso (https://unsplash.com/es/@fernandanuso) for the adorable puppie pic used to test this library.
