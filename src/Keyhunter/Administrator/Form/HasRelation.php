<?php namespace Keyhunter\Administrator\Form;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasRelation {

    protected $relation = null;

    public function setRelation($relation)
    {
        $this->relation = $relation;
        return $this;
    }

    public function hasRelation()
    {
        //return (! is_null($this->relation) && preg_match('~[a-z0-9\_]+\.[a-z0-9\_]+~', $this->relation));

        return ! is_null($this->relation);
    }

    public function loadRelation()
    {
        list($table/*, $field*/) = explode('.', $this->getRelation());
        return $this->getRepository()->$table();
    }

    /**
     * @return array
     * @internal param array $options
     */
    protected function getRelationSegments()
    {
        return explode('.', $this->relation);
    }

    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @param $model
     */
    protected function extractValueFromEloquentRelation($model)
    {
        $relation = call_user_func([$model, $this->relation]);

        if ($relation instanceof BelongsToMany)
        {
            $value = $relation->lists($relation->getOtherKey());
        }
        else  if ($relation instanceof HasOne)
        {
            $value = $model;

            foreach ($this->getRelationSegments() as $segment)
            {
                $value = $value->{$segment};
                if (null === $value) break;
            }
        }

        $this->setValue($value);
    }
}