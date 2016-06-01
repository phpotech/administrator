<?php namespace Keyhunter\Administrator\Form\Type;

use Exception;
use Form;
use Image As Intervention;
use Request;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Keyhunter\Administrator\Form\ImageSize;
use Keyhunter\Administrator\Form\Resizable;

class Image extends File implements Resizable
{
	protected $sizes = [];

	/**
	 * Pattern to save reSized images
	 *
	 * @var string
	 */
	protected $pattern = '[name]_[size].[ext]';

	/**
	 * Naming map -> how images should be saved
	 * @example: by default original image is going to be saved in $name attribute
	 * 			 so having config: thumb => 300x300, side_image => 400x400, big_image => 800x800
	 * 			 to save reSized image we can define something like: image [eloquent field] => big_image
	 * @var $alias
	 */
	protected $alias;

	protected $relation;

	public function renderInput()
	{
		return ($this->value ?
			'<a target="_blank" href="' . $this->getValue() . '">' .
			'	<img src="' . $this->getValue() . '" style="max-width: 200px; max-height: 200px;" />' .
			'</a><br /><br />' : '') .
			Form::file($this->name, $this->attributes);
	}

	public function hasSizes()
	{
		return ! empty($this->sizes);
	}

	/**
	 * Upload & Resize image
	 *
	 * @return array
     */
	public function resize()
	{
		if ($file = Request::file($this->getName()))
		{
			if ($file->isValid())
			{
				$image = Intervention::make($file);

				$location = $this->evaluateLocation();
				$this->validateLocation($location);

				$origName   = $this->evaluateSavePattern($file, null);
				$saveToFile = $location . '/' . $origName;

				$image->save($saveToFile);

				$images = [
					$this->getName() => new SplFileInfo($saveToFile)
				];

				// resize images using sizes list
				foreach ($this->sizes as $sizeName => $rules)
				{
					list($width, $height) = explode('x', (string) $rules);

					$sizedName  = $this->evaluateSavePattern($file, $sizeName);
					$saveToFile	= $location . '/' . $sizedName;

					try
					{
						$image->resize($width, $height)->save($saveToFile);
						$images[$sizeName] = new SplFileInfo($saveToFile);
					}
					catch (Exception $e)
					{
						$images[$sizeName] = $e;
					}
				}

				return $images;
			}
		}
	}

	/**
	 * @param \Symfony\Component\HttpFoundation\File\UploadedFile $fileInfo
	 * @param $size
	 * @return mixed
	 */
	private function evaluateSavePattern(UploadedFile $fileInfo, $size = null)
	{
		$pattern = ($size ? $this->pattern : str_replace('_[size]', '', $this->pattern));

		$name = preg_replace('~(\.(?:jpe?g|png|gif))$~si', '', $fileInfo->getClientOriginalName());

		return strtr($pattern, [
			'[name]' => $name,
			'[size]' => $size,
			'[ext]'  => $fileInfo->getClientOriginalExtension()
		]);
	}

	public function getAliases()
	{
		return (array) $this->alias;
	}

	public function addSize($name, $width, $height)
	{
		$this->sizes[$name] = new ImageSize($width, $height);
	}

	public function setSizes($sizes)
	{
		foreach($sizes as $name => $size)
		{
			if ($size instanceof ImageSize)
			{
				$this->sizes[$name] = $size;
			}
			else
			{
				list($width, $height) = explode('x', $size);
				$this->sizes[$name] = new ImageSize($width, $height);
			}
		}

		return $this;
	}
}