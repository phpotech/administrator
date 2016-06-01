<?php namespace Keyhunter\Administrator\Schema;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Factory implements SchemaInterface
{
    protected $table;

    protected $fields = null;

    protected $classMap = [
        'int'       => 'Type\Int',
        'tinyint'   => 'Type\Int',
        'decimal'   => 'Type\Int',
        'float'     => 'Type\Int',
        'bigint'    => 'Type\Int',
        'char'      => 'Type\Varchar',
        'varchar'   => 'Type\Varchar',
        'text'      => 'Type\Text',
        'tinytext'  => 'Type\Text',
        'mediumtext'=> 'Type\Text',
        'longtext'  => 'Type\Text',
        'date'      => 'Type\DateTime',
        'datetime'  => 'Type\DateTime',
        'timestamp' => 'Type\DateTime',
        'enum'      => 'Type\Enum',
        'set'       => 'Type\Set'
    ];

    /**
     * @var
     */
    private $db;

    /**
     * @var Collection
     */
    private $collection;

    public function __construct(DatabaseManager $db, Collection $collection)
    {
        $this->db = $db;
        $this->collection = $collection;
    }

    public function describe($table)
    {
        $this->setTable($table);

        if (null === $this->fields) {
            $items = [];

            $sql = "DESCRIBE {$this->table}";

            $meta = $this->db->select($sql);
            foreach($meta as $field) {
                list($type, $length, $extra) = $this->_parseType($field->Type);

                $item = [
                    'type'     => $type,
                    'length'   => $length,
                    'nullable' => ('YES' == $field->Null),
                    'primary'  => ("PRI" == $field->Key),
                    'unique'   => ('UNI' == $field->Key),
                    'default'  => $field->Default,
                    'extra'    => $extra
                ];

                $class = __NAMESPACE__ . "\\" . $this->classMap[$type];

                $items[$field->Field] = new $class($field->Field, $item);
            }

            $this->fields = $this->collection->make($items);
        }
        return $this->fields;
    }

    private function _parseType($type)
    {
        preg_match('~^(?P<type>[a-z\_]+)(?:\((?P<length>[^\)]+)\))?\s?(?P<extra>[\w\s]+)?$~si', $type, $matches);

        $type       = strtolower($matches['type']);
        $length     = array_get($matches, 'length', null);
        $extra      = array_get($matches, 'extra', "");

        switch ($type) {
            case 'enum':
            case 'set':
                preg_match_all('~[\w\_]+~si', $length, $values);
                $length = null;
                $extra  = $values[0];
                break;

            default:
                $length = is_numeric($length) ? (int) $length : $length;
                $extra  = ! empty($extra) ? explode(" ", $extra) : null;
                break;
        }

        return [$type, $length, $extra];
    }

    /**
     * Check if field exists in provided table
     *
     * @param $field
     * @throws \Exception
     * @internal param $fieldsCollection
     * @internal param $table
     */
    protected function checkFieldExists($field)
    {
        if (! $this->fields->has($field)) {
            throw new \Exception(sprintf('Table "%s" does not contains field "%s"', $this->table, $field));
        }
    }

    public function get($field)
    {
        $this->describe($this->table);

        $this->checkFieldExists($field);

        return $this->fields->get($field);
    }

    /**
     * @param $table
     * @return $this
     * @throws \Exception
     */
    public function setTable($table)
    {
        if (! $table)
        {
            throw new \Exception('Can not get table meta information. Table is empty.');
        }

        // reset fields if table changed
        if ($table === $this->table)
        {
            $this->fields = null;
        }

        $this->table = $table;

        return $this;
    }
}