<?php namespace Keyhunter\Administrator\Columns;

use Illuminate\Database\Eloquent\Collection;

class Factory
{
    protected $_cleanColumns = [];

    protected $columns       = null;

    public function __construct(array $columns)
    {
        $this->_cleanColumns = $columns;
    }

    /**
     * Get list of columns
     *
     * @param bool $force
     * @return array|null
     */
    public function getColumns($force = false)
    {
        if (null === $this->columns || $force) {
            $this->columns = new Collection();

            foreach ($this->_cleanColumns as $column => $options) {
                // @todo: implement "visibility" concent

                if ($this->isGroup($options)) {
                    $title      = isset($options['title']) ? $options['title'] : $column;
                    $sortField  = isset($options['sort_field']) ? $options['sort_field'] : null;

                    $item       = new Group($column, $title, $options['elements'], $sortField);
                } else {
                    $item       = new Column($column, $options);
                }

                $this->columns->push($item);
            }
        }

        return $this->columns;
    }

    /**
     * @param $options
     * @return bool
     */
    private function isGroup($options)
    {
        return is_array($options) && array_key_exists('elements', $options);
    }
}