<?php


namespace Keyhunter\Administrator;

use Keyhunter\Administrator\Filters\FilterInterface;
use Keyhunter\Administrator\Schema\SchemaInterface;

interface RepositoryInterface
{
    public function setSchema(SchemaInterface $schema);

    /**
     * @param null $scaffoldQueryWith
     * @return $this
     */
    public static function setScaffoldQueryWith($scaffoldQueryWith);

    /**
     * Inject the scaffold Query Builder
     *
     * @param null $scaffoldQueryBuilder
     * @return $this
     */
    public static function setScaffoldQueryBuilder($scaffoldQueryBuilder);

    /**
     * Set the scaffold Filter factory
     *
     * @param FilterInterface $filter
     */
    public static function setScaffoldFilter(FilterInterface $filter);

    /**
     * Get index page results
     *
     * @param int $perPage
     * @return mixed
     */
    public function indexResults($perPage = 20);

    /**
     * Find row by its ID
     *
     * @param $id
     * @return mixed
     */
    public function findRowByID($id);
}