# PHP Thumbnailer

## Speed focused, Imagemagick &amp; WebP powered PHP class designed to simply manipulate images and thumbnails on PHP CRUDs.
### Specially crafted to be used on Laravel projects as an alternative to intervention/image.

The aim of this class is to be able to make multiple thumbnails of images quickly and easily, using ImageMagick and command line CWebP. 

I preferred to use the command line versions rather than the PHP libraries themselves because I was looking for the best possible performance, and all the tests I did with the libraries didn't convince me, especially in the case of uploading dozens of images and having to make 4 or 5 thumbnails of each one. Therefore, it is important to keep in mind that we will need to have the "imagemagick" and "webp" packages installed and that their binaries can be executed by the system user of the web server.

Note: this class is designed to be used as a new object and not by static function calls.

## Use

### Step 1: Instance a new thumbnailer object with 2 parameters*
- The source file (can be a JPG or PNG, everything else will fail)
- The target directory of your local filesystem (must exists before call save() method)

```
$thumbnails = new Thumbnailer();
```

### Step 2: Set some options

Optional: Add your file prefix to generate your files with an speficied name pattern. The order of thumbnail generation is important because the class with append the number of iteration on as a file suffix.

If you don't specify a prefix, filenames will match a pattern of a 8 random hexadecimal string like "abcd1234-1.jpg".

```$thumbnails->setFilePrefix("my-prefix"); // Will generate files named like "my-prefix-01.jpg```

```
$thumbnails->setFilePrefix("my-prefix")
           ->setFormat("jpg")
           ->setQuality(85)
           ->addThumbnail(50,50)
```

```
$thumbnails->setFilePrefix("my-prefix")
           ->setFormat("jpg")
           ->setQuality(85)
           ->addThumbnail(50,50)
           ->save();
```

# Step 3: Add some thumbnail sizes to generate

```
$thumbnails->addThumbnail(1024,768)
           ->addThumbnail(800,600)
           ->addThumbnail(500,50)
           ->addThumbnail(250,50)
           ->addThumbnail(100,50)
           ->addThumbnail(50,25)
```


## Thanks

Thanks to Fernanda Nuso (https://unsplash.com/es/@fernandanuso) for the puppie pic used to test this library <3
