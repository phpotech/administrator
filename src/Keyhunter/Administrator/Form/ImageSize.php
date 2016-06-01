<?php namespace Keyhunter\Administrator\Form;

class ImageSize
{
    protected $width;

    protected $height;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    public function getSize()
    {
        return "{$this->width}x{$this->height}";
    }

    public function __toString()
    {
        return $this->getSize();
    }
}