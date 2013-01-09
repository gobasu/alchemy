Image Manipulation
==================

Creating image object
---------------------
```php
$img = new alchemy\file\Image($path, $preserveTransparency = true);
```
Parameters:
- *$path* image path
- *$preserveTransparency* tells if png/gif images should preserve their transparency

Getting width/height
--------------------
```php
$img->getWidth();
$img->getHeight();
```

Resizing
--------
```php
$img->resize($width, $height, $type = alchemy\file\Image::RESIZE_MAXIMAL);
```
Parameters:
- *$width* new image width
- *$height* new image height
- *$type* tells whatever image should use this values as minimal values `alchemy\file\Image::RESIZE_MINIMAL` or maximal `alchemy\file\Image::RESIZE_MAXIMAL`

Resizing only by `$height`
```php
$img->resize(null, 100);
```

Cropping image
--------------
```php
$img->crop($startX, $startY, $width, $height);
```
Parameters:
- *$startX* x position to start from
- *$startY* y position to start from
- *$width* new image width
- *$height* new image height

Cropping image from center
```php
$img->cropFromCenter($width, $height);
```
Parameters:
- *$width* new image width
- *$height* new image height

Rotating image
--------------
```php
$img->rotate($rotate = 'CW');
```
Parameters:
- *$rotate* can be `CW`(clock wise) or `CCW`(counter clock wise)

Saving file
-----------
```php
$img->save($compression = 100, $file = null);
```
Parameters:
- *$compression* 1-100 (higher - better)
- *$file* (not required) if passed will save as new file otherwise will try to override existing file
