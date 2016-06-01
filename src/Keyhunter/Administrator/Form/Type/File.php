<?php namespace Keyhunter\Administrator\Form\Type;

use Form;
use Request;
use Keyhunter\Administrator\Exception;
use Keyhunter\Administrator\Form\Element;
use Keyhunter\Administrator\Form\Uploadable;

class File extends Element implements Uploadable
{
    const NAMING_ORIGIN   = 'original';

    const NAMING_RANDOM   = 'random';

    const NAMING_CHECKSUM = 'checksum';

    protected $location;

    protected $naming = self::NAMING_CHECKSUM;


    /**
     * The specific defaults for subclasses to override
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The specific rules for subclasses to override
     *
     * @var array
     */
    protected $rules = [
        'location' => 'required',
        //'naming'   => 'in:original,random,checksum',
    ];

    public function renderInput()
    {
        return ($this->value ?
            '<a target="_blank" href="' . $this->getValue() . '">' .
            basename($this->getValue()) .
            '</a><br /><br />' : '') .
        Form::file($this->name, $this->attributes);
    }

    /**
     * This static function is used to perform the actual upload file
     *
     * @return array
     * @throws Exception
     */
    public function upload()
    {
        $location = $this->evaluateLocation();

        $this->validateLocation($location);

        if ($file = Request::file($this->getName())) {
            if ($file->isValid()) {
                if (is_callable($this->naming)) {
                    $filename = $this->naming($file);
                } else {
                    switch ($this->naming) {
                        case static::NAMING_ORIGIN:
                            $filename = $file->getClientOriginalName();
                            break;

                        case static::NAMING_RANDOM:
                            $filename = str_random(10);
                            break;

                        case static::NAMING_CHECKSUM:
                            $hash = md5_file($file->getRealPath());
                            $ext = $file->getClientOriginalExtension();
                            $filename = "{$hash}.{$ext}";
                            break;
                    }
                }

                $filename = rtrim($location, '/') . '/' . ltrim($filename, '/');

                return $file->move($location, $filename);
            }
        }

        return false;
    }

    /**
     * Destroy file
     *
     * @param null $file
     */
    public function destroy($file = null)
    {
        if ($file = $file ?: $this->getValue()) {
            $location = ltrim($file, '/');
            @unlink(base_path("public/{$location}"));
        }
    }

    /**
     * @return mixed
     */
    protected function evaluateLocation()
    {
        // valid location formats: (:id) | (:member_id) | (:any_eloquent_attribute)
        $location = preg_replace_callback('~\(\:([a-z0-9\_]+)\)~si', function ($match) {
            $tableRow = $this->getRepository();

            $field = $match[1];

            if (isset($tableRow->$field)) {
                $value = $tableRow->$field;
            } else if (method_exists($tableRow, $field)) {
                $value = call_user_func([$tableRow, $field]);
            } else {
                $value = '';
            }

            // normalize the path
            // when Polymorphic relations use, the {imageable}_type can contain App\Image like value,
            // which is not valid part of path
            $value = str_replace('\\', '/', $value);
            $value = strtolower($value);

            return $value;
        }, $this->location);

        return base_path("public/" . $location);
    }

    /**
     * @param $location
     * @throws Exception
     */
    protected function validateLocation($location)
    {
        $fileExists = file_exists($location);

        if ((! $fileExists && ! mkdir($location, 0777, true)) || ! is_writeable($location)) {
            throw new Exception("Location [$location] not exists or not writable");
        }
    }

    public function setLocation($location)
    {
        $this->location = trim($location, '/');

        return $this;
    }
}