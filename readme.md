EXIF Based Auto Rotation
---

This is a simple class that allows you to auto-rotate your images based on EXIF data imbedded in JPG and TIFF images.

## Installing

Add `"rtablada/exif": "dev-master"` to your `composer.json` file.

## Use

Using this rotator takes a file path, SPLFileInfo instance, or Symfony\Request\UploadedFile along with an instance of Imagine (here we use GD).
Then call `rotate` and you will be returned an auto-rotated image based on the EXIF data if any is present.

```php
$imagine = new \Imagine\GD\Imagine;
$rotator = new \Rtablada\Exif\ExifRotator($pathToFile, $imagine);

$output = $resizer->rotate();
$output->save($outputPath);
```

## Use with Stapler

This was originally built for use within a Laravel project using [Stapler](https://github.com/CodeSleeve/stapler).
Using this rotator with Stapler is quite simple when defining your styles:

```php
$this->hasAttachedFile('avatar', [
	'styles' => [
		'medium' => '300x300',
		'thumb' => function($file, $imagine) {
			$resizer = \Rtablada\Exif\ExifRotator($file, $imagine);
			return $resizer->rotate();
		}
	]
]);
```

## Shorthand

If you would like to quickly rotate an image you can use `makeAndRotate` which skips the step of having to instantiate the rotator:

```php
\Rtablada\Exif\ExifRotator::makeAndRotate($file, $imagine)->save($output);
```
