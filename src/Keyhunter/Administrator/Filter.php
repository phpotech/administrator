<?php namespace Keyhunter\Administrator;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Keyhunter\Administrator\Filters\FilterInterface;
use Keyhunter\Administrator\Form\Contracts\Element;
use Keyhunter\Administrator\Traits\CallableTrait;

class Filter implements FilterInterface
{
    use CallableTrait;

    /**
     * Types class map
     *
     * @var array
     */
    protected $typesMap = [
        'text'          => 'Filters\Element\Text',
        'select'        => 'Filters\Element\Select',
        'date'          => 'Filters\Element\Date',
        'daterange'     => 'Filters\Element\Daterange',
        'number_range'  => 'Filters\Element\NumberRange',
        'hidden'        => 'Filters\Element\Hidden'
    ];

    /**
     * Filters collection
     *
     * @var Collection|null
     */
    protected $collection = null;

    /**
     * @var Request
     */
    protected  $request;

    /**
     * @param Collection $collection
     * @param Request $request
     * @param array $elements
     */
    public function __construct(Collection $collection, Request $request, array $elements = [])
    {
        $this->collection = $collection;

        $this->request = $request;

        if ($elements)
        {
            $this->addElements($elements);
        }
    }

    public function addElements(array $elements = [])
    {
        foreach($elements as $name => $options)
        {
            $type = $this->validateType($options);

            $filterClass = $this->getFilterClass($type);

            $element = (new $filterClass($name))->initFromArray($options);

            $this->addElement($element);
        }
    }

    public function addElement(Element $element)
    {
        if ($this->request->has($element->getName()))
        {
            $element->setValue($this->request->get($element->getName()));
        }

        $this->collection->push($element);
    }

    public function getElements()
    {
        return $this->collection;
    }

    /**
     * @param $type
     * @return string
     * @internal param $options
     */
    protected function getFilterClass($type)
    {
        $filterClass = __NAMESPACE__ . "\\" . $this->typesMap[$type];
        return $filterClass;
    }


    /**
     * @param $options
     * @return string
     * @throws Exception
     * @internal param $type
     */
    protected function validateType($options)
    {
        $type = isset($options['type']) ? $options['type'] : $this->getDefaultType();

        if (! array_key_exists($type, $this->typesMap))
            throw new Exception(sprintf('Invalid filter type provided: "%s"', $type));

        return $type;
    }

    /**
     * @return string
     * @internal param $type
     */
    protected function getDefaultType()
    {
        return 'text';
    }
}