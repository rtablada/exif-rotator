<?php namespace Rtablada\Exif;

use Imagine\Image\ImagineInterface;

class ExifRotator
{

	/**
	 * Image
	 *
	 * @var mixed
	 */
	protected $file;

	/**
	 * Imagine Instance
	 *
	 * @var \Imagine\Image\ImagineInterface
	 */
	protected $imagine;

	/**
	 * Create Instance of ExifRotator
	 *
	 * @param mixed	$file
	 * @param \Imagine\Image\ImagineInterface $imagine
	 */
	public function __construct($file, ImagineInterface $imagine)
	{
		$this->file = $file;
		$this->imagine = $imagine;
		$this->setExif();
	}

	/**
	 * Rotate the Image either a determined degree or
	 * based on EXIF data
	 *
	 * @param  int $angle
	 * @return \Imageine\Image\ImageInterface
	 */
	public function rotate($angle = null)
	{
		$angle = $this->determineAngle($angle);

		return $this->renderRotatedImage($angle);
	}

	public static function makeAndRotate($file, ImagineInterface $imagine, $angle = null)
	{
		$rotator = new static($file, $imagine);

		return $rotator->rotate($angle);
	}

	/**
	 * Sets EXIF data for object
	 *
	 * @return void
	 */
	protected function setExif()
	{
		$this->determineFilePath();
		if (in_array($this->file->getExtension(), array('jpg', 'jpeg', 'tiff', 'tif'))) {
			$this->exif = exif_read_data($this->path);
		} else {
			$this->exif = array();
		}
	}

	/**
	 * Set path based on file type
	 *
	 * @return void
	 */
	protected function determineFilePath()
	{
		if ($this->file instanceof \SplFileInfo) {
			$this->path = $this->file->getRealPath();
		} else {
			$this->path = $this->file;
		}
	}

	/**
	 * Determine angle of rotation based on passed angle
	 * or EXIF data
	 *
	 * @param  integer $angle
	 * @return integer
	 */
	protected function determineAngle($angle = null)
	{
		if ($angle) {
			return $angle;
		}
		if (!empty($this->exif['Orientation'])) {
		    switch ($this->exif['Orientation']) {
		        case 3:
		            return 180;
		            break;

		        case 6:
		            return 90;
		            break;

		        case 8:
		            return -90;
		            break;
		    }
		}

		return 0;
	}

	/**
	 * Render image after rotation
	 *
	 * @param  integer $angle
	 * @return \Imagin\Image\ImageInterface
	 */
	protected function renderRotatedImage($angle)
	{
		$image = $this->imagine->open($this->path);
		$image->rotate($angle);

		return $image;
	}
}
