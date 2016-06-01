<?php namespace Keyhunter\Administrator\Form;

use App;
use Keyhunter\Administrator\Exception;
use Keyhunter\Administrator\RepositoryInterface;

trait HasRepository {

    static protected $eloquentModel = null;

    /**
     * Populate Eloquent repository into Form Elements
     *
     * @param RepositoryInterface $repository
     * @return null
     */
    static public function setRepository(RepositoryInterface $repository)
    {
        self::$eloquentModel = $repository;
    }

    public function getRepository()
    {
        if (null === self::$eloquentModel)
            throw new Exception('No repository found. Please use Element::setRepository method for this.');

        return self::$eloquentModel;
    }

    /**
     * @throws Exception
     */
    protected function extractValueFromEloquentModel()
    {
        $this->setValue(
            $this->getRepository()->getAttribute($this->getName())
        );
    }

    /**
     * @return bool
     */
    protected function _hasEloquentModel()
    {
        return (null !== self::$eloquentModel);
    }
}